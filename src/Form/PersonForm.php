<?php

namespace App\Form;

use App\Entity\Person;
use App\Record\PersonRecord;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity('email', entityClass: Person::class)]
#[UniqueEntity('phone', entityClass: Person::class)]
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
        public bool $emailSubscription = false,
        public bool $phoneSubscription = false,
    ) {
    }

    public static function fromPersonRecord(PersonRecord $personRecord): static
    {
        return new static(
            firstName: $personRecord->firstName,
            lastName: $personRecord->lastName,
            email: $personRecord->email,
            phone: $personRecord->phone,
            emailSubscription: $personRecord->emailSubscription,
            phoneSubscription: $personRecord->phoneSubscription,
        );
    }

}
