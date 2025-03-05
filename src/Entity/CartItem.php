<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'cart_item')]
class CartItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_produktuW')]
    private ?int $idProduktuW = null;

    #[ORM\Column(name: 'quantity')]
    private ?int $quantity = null;

    #[ORM\Column(name: 'cena_produktu', type: 'decimal', precision: 10, scale: 2)]
    private ?string $cenaProduktu = null;

    #[ORM\Column(name: 'produkt_id')]
    private ?int $produktId = null;

    #[ORM\ManyToOne(targetEntity: Cart::class, inversedBy: 'cartItems')]
    #[ORM\JoinColumn(name: 'cart_id', referencedColumnName: 'id_koszyka')]
    private ?Cart $cart = null;

    public function getIdProduktuW(): ?int
    {
        return $this->idProduktuW;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getCenaProduktu(): ?string
    {
        return $this->cenaProduktu;
    }

    public function setCenaProduktu(string $cenaProduktu): self
    {
        $this->cenaProduktu = $cenaProduktu;
        return $this;
    }

    public function getProduktId(): ?int
    {
        return $this->produktId;
    }

    public function setProduktId(int $produktId): self
    {
        $this->produktId = $produktId;
        return $this;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): self
    {
        $this->cart = $cart;
        return $this;
    }
}
