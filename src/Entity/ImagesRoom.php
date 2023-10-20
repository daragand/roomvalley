<?php

namespace App\Entity;

use App\Repository\ImagesRoomRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImagesRoomRepository::class)]
class ImagesRoom
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\ManyToOne(inversedBy: 'imagesRooms')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Room $room = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath( $path): static
    {
        dump($path);
        $this->path = $path;

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): static
    {
        $this->room = $room;

        return $this;
    }
    //fonction pour vérifier que le chemin de l'image n'est pas vide
    #[ORM\PrePersist]
    public function prePersist()
    {
        if ($this->path === null) {
            throw new \Exception('Le chemin de l\'image ne peut pas être vide.');
        }
    }
    public function __toString(): string
    {
        return $this->path;
    }
}
