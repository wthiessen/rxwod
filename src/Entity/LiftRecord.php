<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\LiftRecordRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 *  @ApiResource(
 *     normalizationContext={"groups"={"lift_record:read"}},
 *     denormalizationContext={"groups"={"lift_record:write"}},
 *     attributes={"order"={"createdAt": "DESC"}},
 * )
 * @ORM\Entity(repositoryClass=LiftRecordRepository::class)
 * @ApiFilter(SearchFilter::class, properties={"exercise": "exact", "wodId": "exact"})
 */
class LiftRecord
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"lift_record:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"lift_record:read", "lift_record:write"})
     */
    private $weight;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"lift_record:read", "lift_record:write"})
     */
    private $wodId;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"lift_record:read", "lift_record:write"})
     */
    private $exercise;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"lift_record:read", "lift_record:write"})
     */
    private $repScheme;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"lift_record:read", "lift_record:write"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"lift_record:read", "lift_record:write"})
     */
    private $comment;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExercise(): ?string
    {
        return $this->exercise;
    }

    public function setExercise(string $exercise): self
    {
        $this->exercise = $exercise;

        return $this;
    }

    public function getRepScheme(): ?string
    {
        return $this->repScheme;
    }

    public function setRepScheme(string $repScheme): self
    {
        $this->repScheme = $repScheme;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return int
     */
    public function getWodId(): int
    {
        return $this->wodId;
    }

    /**
     */
    public function setWodId(?int $wodId): int
    {
        $this->wodId = $wodId;

        return $wodId;
    }
}
