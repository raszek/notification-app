<?php

namespace App\Service\Subscription;

use RuntimeException;

readonly class CsvSubscriptionManager
{

    public function __construct(
        private string $subscriptionFile,
    ) {
    }

    public function addSubscriber(string $id, string $value): void
    {
        $this->createFileIfNotExist();

        if ($this->isSubscribing($id)) {
            throw new RuntimeException('Person is already subscribing');
        }

        $stream = fopen($this->subscriptionFile(), 'a');

        if ($stream === false) {
            throw new RuntimeException('Could not open to append email subscription file');
        }

        fputcsv($stream, [$id, $value]);

        fclose($stream);
    }

    public function removeSubscriber(int $id): void
    {
        $rowNumber = $this->findRowNumberById($id);

        if ($rowNumber === null) {
            throw new RuntimeException('Row number not found');
        }

        $fileOut = file($this->subscriptionFile());

        if ($fileOut === false) {
            throw new RuntimeException('Could not read subscription file');
        }

        unset($fileOut[$rowNumber]);

        file_put_contents($this->subscriptionFile(), implode('', $fileOut));
    }

    public function isSubscribing(string $id): bool
    {
        $this->createFileIfNotExist();

        $record = $this->findSubscriberById($id);

        return $record !== null;
    }

    public function clearAllSubscribers(): void
    {
        $stream = fopen($this->subscriptionFile(), 'w');

        if ($stream === false) {
            throw new RuntimeException('Could not open to write subscription file');
        }

        ftruncate($stream, 0);
    }

    private function findRowNumberById(string $id): ?int
    {
        $stream = fopen($this->subscriptionFile(), 'r');

        if ($stream === false) {
            throw new RuntimeException('Could not open to read email subscription file');
        }

        $i = 0;
        while (($data = fgetcsv($stream, 1000)) !== false) {
            if ($data[0] === $id) {
                fclose($stream);
                return $i;
            }
            $i++;
        }

        return null;
    }

    private function findSubscriberById(string $id): ?array
    {
        $stream = fopen($this->subscriptionFile(), 'r');

        if ($stream === false) {
            throw new RuntimeException('Could not open to read email subscription file');
        }

        while (($data = fgetcsv($stream, 1000)) !== false) {
            if ($data[0] === $id) {
                fclose($stream);
                return $data;
            }
        }

        fclose($stream);
        return null;
    }

    private function createFileIfNotExist(): void
    {
        if (file_exists($this->subscriptionFile())) {
            return;
        }

        touch($this->subscriptionFile());
    }

    private function subscriptionFile(): string
    {
        return $this->subscriptionFile;
    }
}
