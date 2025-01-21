<?php

namespace App\Service\Notification;

use SplObjectStorage;
use SplObserver;

class Message implements \SplSubject
{
    /**
     * @var SplObjectStorage
     */
    private SplObjectStorage $observers;

    private string $content;

    public function __construct()
    {
        $this->observers = new SplObjectStorage();
    }

    public function attach(SplObserver $observer): void
    {
        $this->observers->attach($observer);
    }

    public function detach(SplObserver $observer): void
    {
        $this->observers->detach($observer);
    }

    public function notify(): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    public function sendContent(string $content): void
    {
        $this->content = $content;

        $this->notify();
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
