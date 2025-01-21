<?php

namespace App\Service\Subscription;

use App\Entity\Person;
use App\Notification\EmailNotification;
use App\Service\ProjectDirectoryService;
use SplSubject;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\Mailer\MailerInterface;

#[Autoconfigure(public: true)]
readonly class EmailSubscriptionService implements SubscriptionService
{
    private CsvSubscriptionManager $csvSubscriptionManager;

    public function __construct(
        ProjectDirectoryService $projectDirectoryService,
        private MailerInterface $mailer,
        string $subscriptionFileName
    ) {
        $this->csvSubscriptionManager = new CsvSubscriptionManager(
            $projectDirectoryService->varDirectory().'/'.$subscriptionFileName,
        );
    }

    public function addSubscriber(Person $person): void
    {
        $this->csvSubscriptionManager->addSubscriber($person->getId(), $person->getEmail());
    }

    public function isSubscribing(Person $person): bool
    {
        return $this->csvSubscriptionManager->isSubscribing($person->getId());
    }

    public function removeSubscriber(Person $person): void
    {
        $this->csvSubscriptionManager->removeSubscriber($person->getId());
    }

    public function clearAllSubscribers(): void
    {
        $this->csvSubscriptionManager->clearAllSubscribers();
    }

    public function update(SplSubject $subject): void
    {
        $subscribers = $this->csvSubscriptionManager->getSubscribers();

        foreach ($subscribers as $subscriber) {
            $notification = new EmailNotification($this->mailer, $subscriber['value']);

            $notification->send($subject->getContent());
        }
    }

    public function getFormField(): string
    {
        return 'emailSubscription';
    }
}
