<?php

namespace App\Entity;

use App\Repository\ProductsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductsRepository::class)]
#[ORM\Table(name: 'products')]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idProduct')]
    private ?int $idProduct = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message: 'Field cannot be empty.')]
    #[Assert\Length(min:3, minMessage:'The name must contain {{ limit }} characters minimum')]
    #[Assert\Length(max:30, maxMessage:'The name must must contain {{ limit }} characters maximum')]
    private ?string $name = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Field cannot be empty.')]
    #[Assert\PositiveOrZero]
    private ?float $price = null;

    #[ORM\Column(name: 'quantityInStock')]
    #[Assert\NotBlank(message: 'Field cannot be empty.')]
    #[Assert\PositiveOrZero]
    private ?int $quantityInStock = null;

    #[ORM\Column(length: 1024)]
    #[Assert\NotBlank(message: 'Field cannot be empty.')]
    #[Assert\Length(min:3, minMessage:'The description must contain {{ limit }} characters minimum')]
    #[Assert\Length(max:1024, maxMessage:'The description must must contain {{ limit }} characters maximum')]
    private ?string $description = null;

    #[ORM\Column(length: 255, name: 'imagePath')]
    #[Assert\Length(max:255)]
    private ?string $imagePath = null;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'products', cascade:['persist'])]
    #[ORM\JoinColumn(name: 'idMainCategory', referencedColumnName: 'idCategory')]
    private $mainCategory;

    public function getIdProduct(): ?int
    {
        return $this->idProduct;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getQuantityInStock(): ?int
    {
        return $this->quantityInStock;
    }

    public function setQuantityInStock(string $qty): self
    {
        $this->quantityInStock = $qty;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(string $imagePath): self
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    public function getMainCategory(): ?Category 
    {
        return $this->mainCategory;
    }

    public function setMainCategory(Category $category): self 
    {
        $this->mainCategory = $category;

        return $this;
    }

    public function decrementQuantityInStock(int $quantityToRemove) {
        $this->quantityInStock -= $quantityToRemove;
    }

    public function setNegativeQuantityToZero(){
        if ($this->quantityInStock < 0) {
            $this->quantityInStock = 0;
        }
    }
}
