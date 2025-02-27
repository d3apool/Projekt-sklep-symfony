<?php

namespace App\Entity;

use App\Repository\CartItemRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartItemRepository::class)]
class CartItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quanity = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $cenaProduktu = null;

    #[ORM\Column]
    private ?int $produktID = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuanity(): ?int
    {
        return $this->quanity;
    }

    public function setQuanity(int $quanity): static
    {
        $this->quanity = $quanity;

        return $this;
    }

    public function getCenaProduktu(): ?string
    {
        return $this->cenaProduktu;
    }

    public function setCenaProduktu(string $cenaProduktu): static
    {
        $this->cenaProduktu = $cenaProduktu;

        return $this;
    }

    public function getProduktID(): ?int
    {
        return $this->produktID;
    }

    public function setProduktID(int $produktID): static
    {
        $this->produktID = $produktID;

        return $this;
    }
}
