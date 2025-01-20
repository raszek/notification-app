<?php

namespace App\Form;

use App\Entity\Person;
use Symfony\Component\Validator\Constraints as Assert;

class PersonForm
{

    public function __construct(
        #[Assert\NotBlank()]
        #[Assert\Length(max: 20)]
        public ?string $firstName = null,
        #[Assert\NotBlank()]
        #[Assert\Length(max: 60)]
        public ?string $lastName = null,
        #[Assert\NotBlank()]
        #[Assert\Length(max: 100)]
        #[Assert\Email()]
        public ?string $email = null,
        #[Assert\Regex(Person::PHONE_REGEX)]
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
