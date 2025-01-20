<?php

namespace App\Tests\Service\Person;

use App\Entity\Person;
use App\Factory\PersonFactory;
use App\Form\PersonForm;
use App\Service\Person\PersonEditor;
use App\Service\Person\PersonEditorFactory;
use App\Service\Subscription\EmailSubscriptionService;
use App\Service\Subscription\PhoneSubscriptionService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;

class PersonEditorTest extends KernelTestCase
{

    use Factories;

    /** @test */
    public function email_subscription_can_be_removed_from_person()
    {
        $person = PersonFactory::createOne();

        $this->emailSubscriptionService()->addSubscriber($person->_real());
        $this->phoneSubscriptionService()->addSubscriber($person->_real());

        $personEditor = $this->create($person->_real());

        $personEditor->edit(new PersonForm(
            firstName: 'firstName',
            lastName: 'lastName',
            email: 'test@gmail.com',
            phone: '123123123',
            emailSubscription: false,
            phoneSubscription: false
        ));

        $this->assertFalse($this->emailSubscriptionService()->isSubscribing($person->_real()));
        $this->assertFalse($this->phoneSubscriptionService()->isSubscribing($person->_real()));
    }

    private function create(Person $person): PersonEditor
    {
        $factory = static::getContainer()->get(PersonEditorFactory::class);

        return $factory->create($person);
    }

    private function emailSubscriptionService(): EmailSubscriptionService
    {
        return static::getContainer()->get(EmailSubscriptionService::class);
    }

    private function phoneSubscriptionService(): PhoneSubscriptionService
    {
        return static::getContainer()->get(PhoneSubscriptionService::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->emailSubscriptionService()->clearAllSubscribers();
        $this->phoneSubscriptionService()->clearAllSubscribers();
    }
}
