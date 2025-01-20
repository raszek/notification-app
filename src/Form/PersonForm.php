<?php

namespace App\Form;

class PersonForm
{

    public function __construct(
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $email = null,
        public ?string $phone = null,
    ) {
    }

}
