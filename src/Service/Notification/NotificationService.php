<?php

namespace App\Service\Notification;

use App\Service\Subscription\SubscriptionServiceList;

readonly class NotificationService
{

    public function __construct(
        private SubscriptionServiceList $subscriptionServiceList
    ) {
    }

    public function sendNotifications(string $content): void
    {
        $message = new Message();

        foreach ($this->subscriptionServiceList->subscriptionServices() as $subscriptionService) {
            $message->attach($subscriptionService);
        }

        $message->sendContent($content);
    }
}
