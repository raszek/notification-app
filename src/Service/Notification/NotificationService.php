<?php

namespace App\Service\Notification;

use App\Service\Subscription\EmailSubscriptionService;
use App\Service\Subscription\PhoneSubscriptionService;

readonly class NotificationService
{

    public function __construct(
        private EmailSubscriptionService $emailSubscriptionService,
        private PhoneSubscriptionService $phoneSubscriptionService
    ) {
    }

    public function sendNotifications(string $content): void
    {
        $message = new Message();

        $message->attach($this->emailSubscriptionService);
        $message->attach($this->phoneSubscriptionService);

        $message->sendContent($content);
    }
}
