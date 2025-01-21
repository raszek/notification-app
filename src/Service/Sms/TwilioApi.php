<?php

namespace App\Service\Sms;

class TwilioApi implements SmsApiInterface
{


    public function send(SmsMessage $smsMessage): void
    {
        // Mockup for sending messages
    }
}
