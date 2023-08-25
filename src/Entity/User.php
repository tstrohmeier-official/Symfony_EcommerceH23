<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'profils')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account using this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idProfil')]
    private ?int $idProfil = null;

    #[ORM\Column(length: 150, unique: true)]
    #[Assert\NotBlank(message: 'Field cannot be empty.')]
    #[Assert\Email(message: 'This is not a valid email address.', mode: 'html5-allow-no-tld')]
    private ?string $email = null;

    #[ORM\Column(name: 'lastName')]
    #[Assert\NotBlank(message: 'Field cannot be empty.')]
    #[Assert\Length(min: 2, minMessage: 'Your lastname must contain {{ limit }} characters minimum')]
    #[Assert\Length(max: 30, maxMessage: 'Your lastname must contain {{ limit }} characters maximum')]
    private ?string $lastName = null;

    #[ORM\Column(name: 'firstName')]
    #[Assert\NotBlank(message: 'Field cannot be empty.')]
    #[Assert\Length(min: 2, minMessage: "Your firstname must contain {{ limit }} characters minimum")]
    #[Assert\Length(max: 30, maxMessage: "Your firstname must contain {{ limit }} characters minimum")]
    private ?string $firstName = null;

    /**
     * @var string The hashed password
     */
    #[Assert\NotBlank(message: 'Password field can not be left empty.')]
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Field cannot be empty.')]
    #[Assert\Length(min: 5, minMessage: "The adress must contain {{ limit }} characters minimum")]
    #[Assert\Length(max: 100, maxMessage: "The adress must contain {{ limit }} characters minimum")]
    private ?string $address = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Field cannot be empty.')]
    #[Assert\Length(min: 3, minMessage: "The town must contain {{ limit }} characters minimum")]
    #[Assert\Length(max: 30, maxMessage: "The town must contain {{ limit }} characters minimum")]
    private ?string $town = null;

    #[ORM\Column(name: 'postalCode')]
    #[Assert\NotBlank(message: 'Field cannot be empty.')]
    #[Assert\Length(min: 3, minMessage: "The town must contain {{ limit }} characters minimum")]
    #[Assert\Regex(pattern: "/^([ABCEGHJKLMNPRSTVXY][0-9][ABCEGHJKLMNPRSTVXY])([0-9][ABCEGHJKLMNPRSTVXY][0-9])$/", message: "This input dont respect the Canadien postal format code.")]
    private ?string $postalCode = null;

    #[ORM\Column(length: 2)]
    #[Assert\NotBlank(message: 'Field cannot be empty.')]
    #[Assert\Length(min: 2, minMessage: "The town must contain {{ limit }} characters minimum")]
    #[Assert\Regex(pattern: '/^([A-Z]{2}$)/', message: 'Province format must be of 2 letters')]
    private ?string $province = null;

    #[ORM\Column(length: 10, nullable: true)]
    #[Assert\Regex(pattern: "/^[0-9]{3}[0-9]{3}[0-9]{4}$/", message: "Your phone number must respect the following format: XXX-XXX-XXXX")]
    private ?string $phone = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Order::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $orders;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    // *IMPORTANT: Uncomment for email verification
    // #[ORM\Column(type: 'boolean')]
    // private $isVerified = false;

    public function getIdProfil(): ?int
    {
        return $this->idProfil;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = trim($email);

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = trim($firstName);

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = trim($lastName);

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = trim($address);

        return $this;
    }

    public function getTown(): ?string
    {
        return $this->town;
    }

    public function setTown(string $town): self
    {
        $this->town = trim($town);

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getProvince(): ?string
    {
        return $this->province;
    }

    public function setProvince(string $province): self
    {
        $this->province = $province;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    // *IMPORTANT: Uncomment for email verification
    // public function isVerified(): bool
    // {
    //     return $this->isVerified;
    // }

    // public function setIsVerified(bool $isVerified): self
    // {
    //     $this->isVerified = $isVerified;

    //     return $this;
    // }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setUser($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getUser() === $this) {
                $order->setUser(null);
            }
        }

        return $this;
    }

    public function isThisMyId(int $userId)
    {
        if ($userId === $this->idProfil) {
            return true;
        } else {
            return false;
        }
    }
}
