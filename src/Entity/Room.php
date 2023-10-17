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

    #[ORM\ManyToMany(targetEntity: Equipment::class, inversedBy: 'rooms')]
    private Collection $equipments;

    #[ORM\OneToMany(mappedBy: 'room', targetEntity: ImagesRoom::class, orphanRemoval: true)]
    private Collection $imagesRooms;

    #[ORM\ManyToOne(inversedBy: 'rooms')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Status $status = null;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->ergonomy = new ArrayCollection();
        $this->equipments = new ArrayCollection();
        $this->imagesRooms = new ArrayCollection();
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
     * @return Collection<int, Equipment>
     */
    public function getEquipments(): Collection
    {
        return $this->equipments;
    }

    public function addEquipment(Equipment $equipment, int $quantity): static
    {
        if (!$this->equipments->contains($equipment)) {
            $this->equipments->add($equipment);
            $equipment->addRoom($this, $quantity);
        }

        return $this;
    }

    public function removeEquipment(Equipment $equipment): static
    {
        $this->equipments->removeElement($equipment);

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
}
