<?php

namespace App\Entity;

use App\Repository\PropertyRepository;
use Cocur\Slugify\Slugify;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: PropertyRepository::class)]
#[UniqueEntity('title')]    // unique for title field
class Property
{

    const HEAT = [
        1 => 'Electrique',
        2 => 'Gaz'
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 5, max: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotNull]
    #[Assert\Length(min: 3)]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Range(
        min: 10,
        max: 200,
        notInRangeMessage: 'La surface doit être situé entre {{ min }}m² et {{ max }}m².',
    )]
    private ?int $surface = null;

    #[ORM\Column]
    #[Assert\NotNull]
    private ?int $rooms = null;

    #[ORM\Column]
    #[Assert\NotNull]
    private ?int $bedrooms = null;

    #[ORM\Column]
    #[Assert\NotNull]
    private ?int $floor = null;

    #[ORM\Column]
    #[Assert\NotNull]
    private ?int $price = null;

    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Choice([1, 2])]
    private ?int $heat = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Assert\Regex('/^[0-9]{5}$/')]
    private ?string $postal_code = null;

    #[ORM\Column(options: ["default" => false])]
    private ?bool $sold = false;

    #[ORM\Column]
    #[Assert\NotNull]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToMany(targetEntity: Option::class, inversedBy: 'properties')]
    private Collection $options;

    // Date = at the creation of instance
    public function __construct()
    {
        $this->created_at = new DateTimeImmutable('now', new DateTimeZone('Europe/Paris'));
        $this->options = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    // Slug = formatted title using for Url
    public function getSlug(): ?string
    {
        $slugify = (new Slugify());
        return $slugify->slugify($this->title);
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSurface(): ?int
    {
        return $this->surface;
    }

    public function setSurface(int $surface): self
    {
        $this->surface = $surface;

        return $this;
    }

    public function getRooms(): ?int
    {
        return $this->rooms;
    }

    public function setRooms(int $rooms): self
    {
        $this->rooms = $rooms;

        return $this;
    }

    public function getBedrooms(): ?int
    {
        return $this->bedrooms;
    }

    public function setBedrooms(int $bedrooms): self
    {
        $this->bedrooms = $bedrooms;

        return $this;
    }

    public function getFloor(): ?int
    {
        return $this->floor;
    }

    public function setFloor(int $floor): self
    {
        $this->floor = $floor;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function getFormattedPrice(): string
    {
        return number_format($this->price, 0, '', ' ');
    }


    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getHeat(): ?int
    {
        return $this->heat;
    }

    public function getHeatType(): ?string
    {
        return self::HEAT[$this->heat];
    }

    public function setHeat(int $heat): self
    {
        $this->heat = $heat;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(string $postal_code): self
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function isSold(): ?bool
    {
        return $this->sold;
    }

    public function setSold(bool $sold): self
    {
        $this->sold = $sold;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection<int, Option>
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(Option $option): self
    {
        if (!$this->options->contains($option)) {
            $this->options->add($option);
            $option->addProperty($this);
        }

        return $this;
    }

    public function removeOption(Option $option): self
    {
        if ($this->options->removeElement($option)) {
            $option->removeProperty($this);
        }

        return $this;
    }
}
