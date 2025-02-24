<?php

namespace App\Entity;

use App\Repository\OrdereRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdereRepository::class)]
class Ordere

{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true )]
    private ?string $NumerZamowienia = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $Utworzono = null;

    #[ORM\Column(length: 40)]
    private ?string $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumerZamowienia(): ?string
    {
        return $this->NumerZamowienia;
    }

    public function setNumerZamowienia(string $NumerZamowienia): static
    {
        $this->NumerZamowienia = $NumerZamowienia;

        return $this;
    }

    public function getUtworzono(): ?\DateTimeImmutable
    {
        return $this->Utworzono;
    }

    public function setUtworzono(\DateTimeImmutable $Utworzono): static
    {
        $this->Utworzono = $Utworzono;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
