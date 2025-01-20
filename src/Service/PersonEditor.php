<?php

namespace App\Service;

use App\Entity\Person;
use App\Form\PersonForm;
use Doctrine\ORM\EntityManagerInterface;

readonly class PersonEditor
{

    public function __construct(
        private Person $person,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function edit(PersonForm $form): void
    {
        $updatedPerson = $this->person;

        $updatedPerson->setEmail($form->email);
        $updatedPerson->setName($form->firstName);
        $updatedPerson->setLastName($form->lastName);
        $updatedPerson->setPhone($form->phone);

        $this->entityManager->flush();
    }

}
