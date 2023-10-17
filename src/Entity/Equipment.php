<?php

namespace App\Entity;

use App\Repository\EquipmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EquipmentRepository::class)]
class Equipment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;


    #[ORM\ManyToMany(targetEntity: Software::class, inversedBy: 'equipment')]
    private Collection $software;

    #[ORM\ManyToOne(inversedBy: 'equipment')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeEquipment $type = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\OneToMany(mappedBy: 'equipment', targetEntity: EquipmentRoomQuantity::class, orphanRemoval: true)]
    private Collection $equipmentRoomQuantities;

    public function __construct()
    {
        $this->rooms = new ArrayCollection();
        $this->software = new ArrayCollection();
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

    
    /**
     * @return Collection<int, Software>
     */
    public function getSoftware(): Collection
    {
        return $this->software;
    }

    public function addSoftware(Software $software): static
    {
        if (!$this->software->contains($software)) {
            $this->software->add($software);
        }

        return $this;
    }

    public function removeSoftware(Software $software): static
    {
        $this->software->removeElement($software);

        return $this;
    }

    public function getType(): ?TypeEquipment
    {
        return $this->type;
    }

    public function setType(?TypeEquipment $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

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
            $equipmentRoomQuantity->setEquipment($this);
        }

        return $this;
    }

    public function removeEquipmentRoomQuantity(EquipmentRoomQuantity $equipmentRoomQuantity): static
    {
        if ($this->equipmentRoomQuantities->removeElement($equipmentRoomQuantity)) {
            // set the owning side to null (unless already changed)
            if ($equipmentRoomQuantity->getEquipment() === $this) {
                $equipmentRoomQuantity->setEquipment(null);
            }
        }

        return $this;
    }
}
