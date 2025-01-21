<?php

namespace App\Service\Sms;

readonly class SmsMessage
{
    public function __construct(
        public string $destinationPhone,
        public string $content
    ) {
    }

}
