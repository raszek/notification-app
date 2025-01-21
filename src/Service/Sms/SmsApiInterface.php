<?php

namespace App\Service\Sms;

interface SmsApiInterface
{
    public function send(SmsMessage $smsMessage): void;
}
