<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\Table(name: 'categories')]
#[UniqueEntity(fields: ['category'], message: 'There is already a category using this name')]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idCategory')]
    private ?int $idCategory = null;

    #[ORM\Column(length: 30, unique: true)]
    #[Assert\NotBlank(message: 'Field cannot be empty.')]
    #[Assert\Length(min:3, minMessage:'Category name must be {{ limit }} characters minimum')]
    #[Assert\Length(max:30, maxMessage:'Category max lenght must be {{ limit }} characters maximum')]
    private ?string $category = null;

    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'mainCategory', fetch: 'LAZY')]
    private $products;

    public function getIdCategory(): ?int
    {
        return $this->idCategory;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }
}
