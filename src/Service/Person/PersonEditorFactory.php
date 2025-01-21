<?php

namespace App\Service\Person;

use App\Entity\Person;
use App\Service\Subscription\SubscriptionServiceList;
use Doctrine\ORM\EntityManagerInterface;

readonly class PersonEditorFactory
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private SubscriptionServiceList $subscriptionServiceList
    ) {
    }

    public function create(Person $person): PersonEditor
    {
        return new PersonEditor(
            person: $person,
            entityManager: $this->entityManager,
            subscriptionServiceList: $this->subscriptionServiceList
        );
    }

}
