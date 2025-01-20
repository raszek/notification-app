<?php

namespace App\Service;

use App\Entity\Person;
use App\Form\PersonForm;
use Doctrine\ORM\EntityManagerInterface;

readonly class PersonService
{

    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
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

        return $newPerson;
    }

    public function remove(Person $person): void
    {
        $this->entityManager->remove($person);

        $this->entityManager->flush();
    }

}
