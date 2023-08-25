<?php

namespace App\Entity;

use App\Repository\PurchaseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchaseRepository::class)]
#[ORM\Table(name:'purchases')]
class Purchase
{
    public function __construct($product)
    {
        // J'avais déjà la quantité dans mon constructeur
        $this->quantity = 1;
        $this->product = $product;
        $this->price = $product->getPrice();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'idPurchase')]
    private ?int $idPurchase = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'purchases')]
    #[ORM\JoinColumn(nullable: false, name:'idOrder', referencedColumnName:'idOrder')]
    private ?Order $orderRelated = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, name:'idProduct', referencedColumnName:'idProduct')]
    private ?Product $product = null;

    public function getIdPurchase(): ?int
    {
        return $this->idPurchase;
    }

    public function getPrice(): ?float
    {
        return $this->price;
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

    public function getOrderRelated(): ?Order
    {
        return $this->orderRelated;
    }

    public function setOrderRelated(?Order $orderRelated): self
    {
        $this->orderRelated = $orderRelated;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function getTotal(){
        return $this->price * $this->quantity;
    }

    public function decrementQuantityInStock() {
        $this->product->decrementQuantityInStock($this->quantity);
    }

    public function getQuantityInStock() {
        return $this->product->getQuantityInStock();
    }

    public function getProductName() {
        return $this->product->getName();
    }
}
