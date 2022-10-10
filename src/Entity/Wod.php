<?php

// TODO for time, rounds
// TODO weight Male/Female Rx
// TODO Rest time

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use App\Repository\WodRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"wod:read"}},
 *     denormalizationContext={"groups"={"wod:write"}},
 *     attributes={"order"={"createdAt": "DESC"}}
 * )
 * @ORM\Entity(repositoryClass=WodRepository::class)
 * @ApiFilter(SearchFilter::class, properties={"createdAt": "partial"})
 */
class Wod
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"wod:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"wod:read", "wod:write", "user:write"})
     */
    private $name;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"wod:read", "wod:write"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=1000)
     * @Groups({"wod:read", "wod:write", "user:write"})
     */
    private $wod;

    public function __construct()
    {
//        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getWod(): ?string
    {
        return nl2br($this->wod);
    }

    public function setWod(?string $wod): self
    {
        $this->wod = strip_tags($wod);

        return $this;
    }

    /*
     * @SerializedName("wod")
     */
    public function getTextWod(?string $wod): self
    {
        $this->wod = nl2br($wod);

        return $this;
    }
}
