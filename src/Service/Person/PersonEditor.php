<?php

namespace App\Service\Person;

use App\Entity\Person;
use App\Form\PersonForm;
use App\Service\Subscription\EmailSubscriptionService;
use App\Service\Subscription\PhoneSubscriptionService;
use Doctrine\ORM\EntityManagerInterface;

readonly class PersonEditor
{

    public function __construct(
        private Person $person,
        private EntityManagerInterface $entityManager,
        private EmailSubscriptionService $emailSubscriptionService,
        private PhoneSubscriptionService $phoneSubscriptionService,
    ) {
    }

    public function edit(PersonForm $form): void
    {
        $updatedPerson = $this->person;

        $updatedPerson->setEmail($form->email);
        $updatedPerson->setName($form->firstName);
        $updatedPerson->setLastName($form->lastName);
        $updatedPerson->setPhone($form->phone);

        if ($form->emailSubscription && !$this->emailSubscriptionService->isSubscribing($updatedPerson)) {
            $this->emailSubscriptionService->addSubscriber($updatedPerson);
        } else if (!$form->emailSubscription && $this->emailSubscriptionService->isSubscribing($updatedPerson)) {
            $this->emailSubscriptionService->removeSubscriber($updatedPerson);
        }

        if ($form->phoneSubscription && !$this->phoneSubscriptionService->isSubscribing($updatedPerson)) {
            $this->phoneSubscriptionService->addSubscriber($updatedPerson);
        } else if (!$form->phoneSubscription && $this->phoneSubscriptionService->isSubscribing($updatedPerson)) {
            $this->phoneSubscriptionService->removeSubscriber($updatedPerson);
        }


        $this->entityManager->flush();
    }

}
