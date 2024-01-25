<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
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
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: MenuItem::class, inversedBy: 'orders')]
    private Collection $dishes;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    private ?User $customer = null;

    #[ORM\OneToMany(mappedBy: 'userOrder', targetEntity: OrderItem::class)]
    private Collection $orderItems;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $time = null;

    #[ORM\Column]
    private ?float $totalPrice = null;

    public function __construct()
    {
        $this->dishes = new ArrayCollection();
        $this->orderItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, MenuItem>
     */
    public function getDishes(): Collection
    {
        return $this->dishes;
    }

    public function addDish(MenuItem $dish): static
    {
        if (!$this->dishes->contains($dish)) {
            $this->dishes->add($dish);
        }

        return $this;
    }

    public function removeDish(MenuItem $dish): static
    {
        $this->dishes->removeElement($dish);

        return $this;
    }

    public function getCustomer(): ?User
    {
        return $this->customer;
    }

    public function setCustomer(?User $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return Collection<int, OrderItem>
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItem $orderItem): static
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems->add($orderItem);
            $orderItem->setUserOrder($this);
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $orderItem): static
    {
        if ($this->orderItems->removeElement($orderItem)) {
            if ($orderItem->getUserOrder() === $this) {
                $orderItem->setUserOrder(null);
            }
        }

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

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): static
    {
        $this->time = $time;

        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(float $totalPrice): static
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getDishQuantity( MenuItem $dish): int
    {
        foreach ($this->getOrderItems() as $orderItem) {
            if ($orderItem->getMenuItem() === $dish) {
                return $orderItem->getQuantity();
            }
        }

        return 0;
    }

}
