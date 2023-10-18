<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?int $capacity = null;

    #[ORM\Column(nullable: true)]
    private ?float $price = null;

    #[ORM\OneToMany(mappedBy: 'room', targetEntity: Reservation::class)]
    private Collection $reservations;

    #[ORM\ManyToOne(inversedBy: 'rooms')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Address $address = null;

    #[ORM\ManyToMany(targetEntity: Ergonomy::class, inversedBy: 'rooms')]
    private Collection $ergonomy;

    

    #[ORM\OneToMany(mappedBy: 'room', targetEntity: ImagesRoom::class, orphanRemoval: true)]
    private Collection $imagesRooms;

    #[ORM\ManyToOne(inversedBy: 'rooms')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Status $status = null;

    #[ORM\OneToMany(mappedBy: 'room', targetEntity: EquipmentRoomQuantity::class, orphanRemoval: true)]
    private Collection $equipmentRoomQuantities;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column]
    private ?int $capacityMin = null;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->ergonomy = new ArrayCollection();
        $this->imagesRooms = new ArrayCollection();
        $this->equipmentRoomQuantities = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(?int $capacity): static
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setRoom($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getRoom() === $this) {
                $reservation->setRoom(null);
            }
        }

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): static
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, Ergonomy>
     */
    public function getErgonomy(): Collection
    {
        return $this->ergonomy;
    }

    public function addErgonomy(Ergonomy $ergonomy): static
    {
        if (!$this->ergonomy->contains($ergonomy)) {
            $this->ergonomy->add($ergonomy);
        }

        return $this;
    }

    public function removeErgonomy(Ergonomy $ergonomy): static
    {
        $this->ergonomy->removeElement($ergonomy);

        return $this;
    }

   
    /**
     * @return Collection<int, ImagesRoom>
     */
    public function getImagesRooms(): Collection
    {
        return $this->imagesRooms;
    }

    public function addImagesRoom(ImagesRoom $imagesRoom): static
    {
        if (!$this->imagesRooms->contains($imagesRoom)) {
            $this->imagesRooms->add($imagesRoom);
            $imagesRoom->setRoom($this);
        }

        return $this;
    }

    public function removeImagesRoom(ImagesRoom $imagesRoom): static
    {
        if ($this->imagesRooms->removeElement($imagesRoom)) {
            // set the owning side to null (unless already changed)
            if ($imagesRoom->getRoom() === $this) {
                $imagesRoom->setRoom(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, EquipmentRoomQuantity>
     */
    public function getEquipmentRoomQuantities(): Collection
    {
        return $this->equipmentRoomQuantities;
    }

    public function addEquipmentRoomQuantity(EquipmentRoomQuantity $equipmentRoomQuantity): static
    {
        if (!$this->equipmentRoomQuantities->contains($equipmentRoomQuantity)) {
            $this->equipmentRoomQuantities->add($equipmentRoomQuantity);
            $equipmentRoomQuantity->setRoom($this);
        }

        return $this;
    }

    public function removeEquipmentRoomQuantity(EquipmentRoomQuantity $equipmentRoomQuantity): static
    {
        if ($this->equipmentRoomQuantities->removeElement($equipmentRoomQuantity)) {
            // set the owning side to null (unless already changed)
            if ($equipmentRoomQuantity->getRoom() === $this) {
                $equipmentRoomQuantity->setRoom(null);
            }
        }

        return $this;
    }
    
    public function __toString()
    {
        return $this->name;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCapacityMin(): ?int
    {
        return $this->capacityMin;
    }

    public function setCapacityMin(int $capacityMin): static
    {
        $this->capacityMin = $capacityMin;

        return $this;
    }
}
