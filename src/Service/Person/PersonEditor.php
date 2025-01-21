<?php

namespace App\Service\Person;

use App\Entity\Person;
use App\Form\PersonForm;
use App\Service\Subscription\SubscriptionServiceList;
use Doctrine\ORM\EntityManagerInterface;

readonly class PersonEditor
{

    public function __construct(
        private Person $person,
        private EntityManagerInterface $entityManager,
        private SubscriptionServiceList $subscriptionServiceList
    ) {
    }

    public function edit(PersonForm $form): void
    {
        $updatedPerson = $this->person;

        $updatedPerson->setEmail($form->email);
        $updatedPerson->setName($form->firstName);
        $updatedPerson->setLastName($form->lastName);
        $updatedPerson->setPhone($form->phone);

        foreach ($this->subscriptionServiceList->subscriptionServices() as $subscriptionService) {
            $formField = $subscriptionService->getFormField();
            if ($form->$formField && !$subscriptionService->isSubscribing($updatedPerson)) {
                $subscriptionService->addSubscriber($updatedPerson);
            } else if (!$form->$formField && $subscriptionService->isSubscribing($updatedPerson)) {
                $subscriptionService->removeSubscriber($updatedPerson);
            }
        }

        $this->entityManager->flush();
    }

}
