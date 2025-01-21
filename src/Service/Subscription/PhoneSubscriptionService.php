<?php

namespace App\Service\Subscription;

use App\Entity\Person;
use App\Notification\SmsNotification;
use App\Service\ProjectDirectoryService;
use App\Service\Sms\SmsApiInterface;
use SplSubject;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(public: true)]
readonly class PhoneSubscriptionService implements \SplObserver
{
    private CsvSubscriptionManager $csvSubscriptionManager;

    public function __construct(
        ProjectDirectoryService $projectDirectoryService,
        private SmsApiInterface $smsApi,
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

    public function update(SplSubject $subject): void
    {
        $subscribers = $this->csvSubscriptionManager->getSubscribers();

        foreach ($subscribers as $subscriber) {
            $notification = new SmsNotification($this->smsApi, $subscriber['value']);

            $notification->send($subject->getContent());
        }
    }
}
