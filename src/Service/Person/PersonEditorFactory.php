<?php

namespace App\Service\Person;

use App\Entity\Person;
use App\Service\Subscription\EmailSubscriptionService;
use App\Service\Subscription\PhoneSubscriptionService;
use Doctrine\ORM\EntityManagerInterface;

readonly class PersonEditorFactory
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private EmailSubscriptionService $emailSubscriptionService,
        private PhoneSubscriptionService $phoneSubscriptionService,
    ) {
    }

    public function create(Person $person): PersonEditor
    {
        return new PersonEditor(
            person: $person,
            entityManager: $this->entityManager,
            emailSubscriptionService: $this->emailSubscriptionService,
            phoneSubscriptionService: $this->phoneSubscriptionService
        );
    }

}
