<?php

namespace App\Tests\Service\Person;

use App\Form\PersonForm;
use App\Service\Person\PersonService;
use App\Service\Subscription\EmailSubscriptionService;
use App\Service\Subscription\PhoneSubscriptionService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PersonServiceTest extends KernelTestCase
{

    /** @test */
    public function person_email_subscriptions_are_saved_to_file()
    {
        $personService = $this->create();

        $person = $personService->create(new PersonForm(
            firstName: 'First',
            lastName: 'Last',
            email: 'some@gmail.com',
            phone: '123123123',
            emailSubscription: true,
            phoneSubscription: false
        ));

        $emailSubscriptionService = $this->emailSubscriptionService();
        $this->assertTrue($emailSubscriptionService->isSubscribing($person));

        $phoneSubscriptionService = $this->phoneSubscriptionService();
        $this->assertFalse($phoneSubscriptionService->isSubscribing($person));
    }

    /** @test */
    public function person_phone_subscriptions_are_saved_to_file()
    {
        $personService = $this->create();

        $person = $personService->create(new PersonForm(
            firstName: 'First',
            lastName: 'Last',
            email: 'some@gmail.com',
            phone: '123123123',
            emailSubscription: false,
            phoneSubscription: true
        ));

        $emailSubscriptionService = $this->emailSubscriptionService();
        $this->assertFalse($emailSubscriptionService->isSubscribing($person));

        $phoneSubscriptionService = $this->phoneSubscriptionService();
        $this->assertTrue($phoneSubscriptionService->isSubscribing($person));
    }

    protected function tearDown(): void
    {
        $this->emailSubscriptionService()->clearAllSubscribers();
        $this->phoneSubscriptionService()->clearAllSubscribers();

        parent::tearDown();
    }

    private function create(): PersonService
    {
        return static::getContainer()->get(PersonService::class);
    }

    private function emailSubscriptionService(): EmailSubscriptionService
    {
        return static::getContainer()->get(EmailSubscriptionService::class);
    }

    private function phoneSubscriptionService(): PhoneSubscriptionService
    {
        return static::getContainer()->get(PhoneSubscriptionService::class);
    }

}
