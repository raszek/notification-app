<?php

namespace App\Service\Subscription;

use Generator;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Psr\Container\ContainerInterface;

readonly class SubscriptionServiceList implements ServiceSubscriberInterface
{

    public function __construct(
        private ContainerInterface $container
    ) {
    }

    public static function getSubscribedServices(): array
    {
        return [
            EmailSubscriptionService::class,
            PhoneSubscriptionService::class
        ];
    }

    /**
     * @return Generator<SubscriptionService>
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function subscriptionServices(): Generator
    {
        foreach (self::getSubscribedServices() as $subscribedService) {
            $subscriptionService = $this->container->get($subscribedService);

            if (!$subscriptionService instanceof SubscriptionService) {
                throw new \RuntimeException('Subscription service must implement interface SubscriptionService');
            }

            yield $subscriptionService;
        }
    }
}
