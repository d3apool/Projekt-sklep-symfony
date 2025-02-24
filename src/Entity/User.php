<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $NazwaUzytkownika = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $Haslo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNazwaUzytkownika(): ?string
    {
        return $this->NazwaUzytkownika;
    }

    public function setNazwaUzytkownika(string $NazwaUzytkownika): static
    {
        $this->NazwaUzytkownika = $NazwaUzytkownika;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getHaslo(): ?string
    {
        return $this->Haslo;
    }

    public function setHaslo(string $Haslo): static
    {
        $this->Haslo = $Haslo;

        return $this;
    }
}
