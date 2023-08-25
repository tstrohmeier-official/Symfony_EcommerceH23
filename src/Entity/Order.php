<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use PHPUnit\TextUI\XmlConfiguration\Constant;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\Unique;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: 'orders')]
class Order
{

    public function __construct(User $user, String $stripeIntent)
    {
        date_default_timezone_set('Greenwich');
        $this->orderDate = new DateTime();
        $this->state = Constants::STATE_PREPARING;
        $this->rateTPS = Constants::TPS;
        $this->rateTVQ = Constants::TVQ;
        $this->deliveryFee = Constants::DELIVERY_FEE;
        $this->purchases = new ArrayCollection();
        $this->user = $user;
        $this->stripeIntent = $stripeIntent;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idOrder')]
    private ?int $idOrder = null;

    #[ORM\Column(name: 'orderDate', type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $orderDate = null;

    #[ORM\Column(name: 'deliveryDate', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $deliveryDate = null;

    #[ORM\Column(name: 'rateTPS')]
    private ?float $rateTPS = null;

    #[ORM\Column(name: 'rateTVQ')]
    private ?float $rateTVQ = null;

    #[ORM\Column(name: 'deliveryFee')]
    private ?float $deliveryFee = null;

    #[ORM\Column(length: 85)]
    private ?string $state = null;

    #[ORM\Column(name: 'stripeIntent', length: 255, unique: true)]
    private ?string $stripeIntent = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false, name: 'idProfil', referencedColumnName: 'idProfil')]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'orderRelated', targetEntity: Purchase::class, orphanRemoval: true)]
    private Collection $purchases;

    private function calculateTaxes($total)
    {
        return $total * ($this->rateTPS + $this->rateTVQ);
    }

    public function getIdOrder(): ?int
    {
        return $this->idOrder;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->orderDate;
    }

    public function setOrderDate(\DateTimeInterface $orderDate): self
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    public function getDeliveryDate(): ?\DateTimeInterface
    {
        return $this->deliveryDate;
    }

    public function setDeliveryDate(\DateTimeInterface $deliveryDate = null): self
    {
        $this->deliveryDate = $deliveryDate;

        return $this;
    }

    public function getRateTPS(): ?float
    {
        return $this->rateTPS;
    }

    public function getRateTVQ(): ?float
    {
        return $this->rateTVQ;
    }

    public function getDeliveryFee(): ?float
    {
        return $this->deliveryFee;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = trim($state);

        return $this;
    }

    public function getStripeIntent(): ?string
    {
        return $this->stripeIntent;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @return Collection<int, Purchase>
     */
    public function getProducts(): Collection
    {
        return $this->purchases;
    }

    public function addProduct(Purchase $purchase): self
    {
        if (!$this->purchases->contains($purchase)) {
            $this->purchases->add($purchase);
            $purchase->setOrderRelated($this);
        }

        return $this;
    }

    public function removeProduct(Purchase $purchase): self
    {
        if ($this->purchases->removeElement($purchase)) {
            // set the owning side to null (unless already changed)
            if ($purchase->getOrderRelated() === $this) {
                $purchase->setOrderRelated(null);
            }
        }

        return $this;
    }

    public function getTotalPrice()
    {
        $total = 0;

        foreach ($this->purchases as $purchase) {
            $total += ($purchase->getPrice() * $purchase->getQuantity());
        }

        $taxes = $this->calculateTaxes($total);
        $total += ($taxes + $this->deliveryFee);

        return round($total, 2, PHP_ROUND_HALF_UP);
    }

    public function getSubTotal()
    {
        $total = 0;

        foreach ($this->purchases as $purchase) {
            $total += ($purchase->getPrice() * $purchase->getQuantity());
        }

        return round($total, 2, PHP_ROUND_HALF_UP);
    }

    public function getUserId(){
        return $this->user->getIdProfil();
    }

    public function isDelivered() {
        if ($this->state == Constants::STATE_DELIVERED) {
            return true;
        }
        return false;
    }
}
