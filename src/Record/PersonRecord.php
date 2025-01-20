<?php

namespace App\Record;

class PersonRecord
{
    public function __construct(
        public int $id,
        public string $firstName,
        public string $lastName,
        public string $email,
        public string $phone,
        public bool $emailSubscription,
        public bool $phoneSubscription,
    ) {
    }


}
