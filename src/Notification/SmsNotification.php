<?php

namespace App\Notification;

use App\Service\Sms\SmsApiInterface;
use App\Service\Sms\SmsMessage;

readonly class SmsNotification implements Notification
{

    public function __construct(
        private SmsApiInterface $smsApi,
        private string $destinationPhone
    ) {
    }

    public function send(string $content): void
    {
        $message = new SmsMessage(
            destinationPhone: $this->destinationPhone,
            content: $content
        );

        $this->smsApi->send($message);
    }
}
