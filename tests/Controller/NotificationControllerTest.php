<?php

namespace App\Tests\Controller;

use App\Factory\PersonFactory;
use App\Service\Subscription\EmailSubscriptionService;
use App\Service\Subscription\PhoneSubscriptionService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;

class NotificationControllerTest extends WebTestCase
{

    use Factories;

    /** @test */
    public function user_can_send_notifications_to_people()
    {
        $client = static::createClient();
        $client->followRedirects();

        $person = PersonFactory::createOne();

        $this->phoneSubscriptionService()->addSubscriber($person->_real());
        $this->emailSubscriptionService()->addSubscriber($person->_real());

        $crawler = $client->request('GET', '/notifications/send');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Send')->form();

        $client->submit($form, [
            'notification[content]' => 'Some content to send'
        ]);

        $this->assertResponseIsSuccessful();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->phoneSubscriptionService()->clearAllSubscribers();
        $this->emailSubscriptionService()->clearAllSubscribers();
    }

    private function phoneSubscriptionService(): PhoneSubscriptionService
    {
        return static::getContainer()->get(PhoneSubscriptionService::class);
    }

    private function emailSubscriptionService(): EmailSubscriptionService
    {
        return static::getContainer()->get(EmailSubscriptionService::class);
    }
}
