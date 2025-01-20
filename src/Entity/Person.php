<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonRepository::class)]
class Person
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $name;

    #[ORM\Column(length: 60)]
    private ?string $lastname;

    #[ORM\Column(length: 100)]
    private ?string $email;

    #[ORM\Column(length: 12)]
    private ?string $phone;

    public function __construct(
        string $name,
        string $lastname,
        string $email,
        string $phone
    ) {
        $this->name = $name;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->phone = $phone;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }
}
