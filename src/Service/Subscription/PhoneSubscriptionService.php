<?php

namespace App\Service\Subscription;

use App\Entity\Person;
use App\Service\ProjectDirectoryService;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(public: true)]
readonly class PhoneSubscriptionService
{
    private CsvSubscriptionManager $csvSubscriptionManager;

    public function __construct(
        ProjectDirectoryService $projectDirectoryService,
        string $subscriptionFileName
    ) {
        $this->csvSubscriptionManager = new CsvSubscriptionManager(
            $projectDirectoryService->varDirectory().'/'.$subscriptionFileName,
        );
    }

    public function addSubscriber(Person $person): void
    {
        $this->csvSubscriptionManager->addSubscriber($person->getId(), $person->getPhone());
    }

    public function removeSubscriber(Person $person): void
    {
        $this->csvSubscriptionManager->removeSubscriber($person->getId());
    }

    public function isSubscribing(Person $person): bool
    {
        return $this->csvSubscriptionManager->isSubscribing($person->getId());
    }

    public function clearAllSubscribers(): void
    {
        $this->csvSubscriptionManager->clearAllSubscribers();
    }

}
