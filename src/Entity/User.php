<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]  ## for PgSQL compatibility
#[UniqueEntity(fields: ['username'], message: 'Il y a déjà un compte avec ce nom.')]
class User implements UserInterface, Serializable, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    // UserInterface methods
    public function getRoles(): array
    {
        return ['ROLE_ADMIN'];
    }
    public function eraseCredentials(): void
    {
    }
    public function getUserIdentifier(): string
    {
        return $this->getUsername();
    }

    // Serializable methods
    public function serialize(): ?string
    {
        return serialize(
            [
                $this->id,
                $this->username,
                $this->password
            ]
        );
    }
    public function unserialize(string $serializedDatas): void
    {
        list(
            $this->id,
            $this->username,
            $this->password
        ) = unserialize($serializedDatas, ['allowed_classes' => false]);
    }
}
