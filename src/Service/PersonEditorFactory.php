<?php

namespace App\Service;

use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;

readonly class PersonEditorFactory
{

    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function create(Person $person): PersonEditor
    {
        return new PersonEditor(
            person: $person,
            entityManager: $this->entityManager
        );
    }

}
