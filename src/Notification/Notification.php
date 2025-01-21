<?php

namespace App\Notification;

/**
 * This notification interface was initially designed for sms. Email notification is adapter for this interface.
 */
interface Notification
{
    public function send(string $content): void;
}
