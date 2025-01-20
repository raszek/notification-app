<?php

namespace App\Form;

use App\Entity\Person;

class PersonForm
{

    public function __construct(
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $email = null,
        public ?string $phone = null,
    ) {
    }

    public static function fromPerson(Person $person): static
    {
        return new static(
            firstName: $person->getName(),
            lastName: $person->getLastName(),
            email: $person->getEmail(),
            phone: $person->getPhone(),
        );
    }

}
