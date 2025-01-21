<?php

namespace App\Service\Person;

use App\Entity\Person;
use App\Form\PersonForm;
use App\Record\PersonRecord;
use App\Repository\PersonRepository;
use App\Service\Subscription\SubscriptionServiceList;
use Doctrine\ORM\EntityManagerInterface;

readonly class PersonService
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private SubscriptionServiceList $subscriptionServiceList,
        private PersonRepository $personRepository,
    ) {
    }

    public function getRecord(Person $person): PersonRecord
    {
        $record = [
            'id' => $person->getId(),
            'firstName' => $person->getName(),
            'lastName' =>  $person->getLastName(),
            'email' => $person->getEmail(),
            'phone' => $person->getPhone(),
        ];

        foreach ($this->subscriptionServiceList->subscriptionServices() as $subscriptionService) {
            $record[$subscriptionService->getFormField()] = $subscriptionService->isSubscribing($person);
        }

        return new PersonRecord(...$record);
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

        foreach ($this->subscriptionServiceList->subscriptionServices() as $subscriptionService) {
            $formField = $subscriptionService->getFormField();
            if ($form->$formField) {
                $subscriptionService->addSubscriber($newPerson);
            }
        }

        return $newPerson;
    }

    public function remove(Person $person): void
    {
        $this->entityManager->remove($person);

        $this->entityManager->flush();
    }

}
