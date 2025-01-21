<?php

namespace App\Form;

use Symfony\Component\Validator\Constraints\NotBlank;

class NotificationForm
{

    public function __construct(
        #[NotBlank]
        public ?string $content = null
    ) {
    }
}
