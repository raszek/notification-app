<?php

namespace App\Service\Subscription;

use App\Entity\Person;
use SplObserver;

interface SubscriptionService extends SplObserver
{

    public function getFormField(): string;

    public function addSubscriber(Person $person): void;

    public function isSubscribing(Person $person): bool;
}
