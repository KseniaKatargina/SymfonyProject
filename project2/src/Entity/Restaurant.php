<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RestaurantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RestaurantRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => ['security' => 'is_granted("ROLE_USER")'],
        'post' => ['security' => 'is_granted("ROLE_ADMIN")'],
    ],
    itemOperations: [
        'get' => ['security' => 'is_granted("ROLE_USER")'],
        'put' => ['security' => 'is_granted("ROLE_ADMIN")'],
        'delete' => ['security' => 'is_granted("ROLE_ADMIN")'],
    ],
)]
class Restaurant
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column]
    private ?int $rating = null;

    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: MenuItem::class)]
    private Collection $menuItems;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $imagePath = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cuisine = null;



    public function __construct()
    {
        $this->menuItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * @return Collection<int, MenuItem>
     */
    public function getMenuItems(): Collection
    {
        return $this->menuItems;
    }

    public function addMenuItem(MenuItem $menuItem): static
    {
        if (!$this->menuItems->contains($menuItem)) {
            $this->menuItems->add($menuItem);
            $menuItem->setRestaurant($this);
        }

        return $this;
    }

    public function removeMenuItem(MenuItem $menuItem): static
    {
        if ($this->menuItems->removeElement($menuItem)) {
            // set the owning side to null (unless already changed)
            if ($menuItem->getRestaurant() === $this) {
                $menuItem->setRestaurant(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(string $imagePath): static
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    public function getCuisine(): ?string
    {
        return $this->cuisine;
    }

    public function setCuisine(?string $cuisine): static
    {
        $this->cuisine = $cuisine;

        return $this;
    }

}
