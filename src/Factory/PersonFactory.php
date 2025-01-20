<?php

namespace App\Factory;

use App\Entity\Person;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Person>
 */
final class PersonFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Person::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'email' => self::faker()->email(),
            'lastname' => self::faker()->lastName(),
            'name' => self::faker()->firstName(),
            'phone' => self::faker()->regexify(Person::PHONE_REGEX),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this;
    }
}
