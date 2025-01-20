<?php

namespace App\Service\Person;

use App\Entity\Person;
use App\Form\PersonForm;
use App\Record\PersonRecord;
use App\Repository\PersonRepository;
use App\Service\Subscription\EmailSubscriptionService;
use App\Service\Subscription\PhoneSubscriptionService;
use Doctrine\ORM\EntityManagerInterface;

readonly class PersonService
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private EmailSubscriptionService $emailSubscriptionService,
        private PhoneSubscriptionService $phoneSubscriptionService,
        private PersonRepository $personRepository,
    ) {
    }

    public function getRecord(Person $person): PersonRecord
    {
        return new PersonRecord(
            id: $person->getId(),
            firstName: $person->getName(),
            lastName: $person->getLastName(),
            email: $person->getEmail(),
            phone: $person->getPhone(),
            emailSubscription: $this->emailSubscriptionService->isSubscribing($person),
            phoneSubscription: $this->phoneSubscriptionService->isSubscribing($person)
        );
    }

    /**
     * @return PersonRecord[]
     */
    public function list(): array
    {
        $people = $this->personRepository->findAll();

        $records = [];
        foreach ($people as $person) {
            $records[] = $this->getRecord($person);
        }

        return $records;
    }

    public function create(PersonForm $form): Person
    {
        $newPerson = new Person(
            name: $form->firstName,
            lastname: $form->lastName,
            email: $form->email,
            phone: $form->phone,
        );

        $this->entityManager->persist($newPerson);

        $this->entityManager->flush();

        if ($form->emailSubscription) {
            $this->emailSubscriptionService->addSubscriber($newPerson);
        }

        if ($form->phoneSubscription) {
            $this->phoneSubscriptionService->addSubscriber($newPerson);
        }

        return $newPerson;
    }

    public function remove(Person $person): void
    {
        $this->entityManager->remove($person);

        $this->entityManager->flush();
    }

}
