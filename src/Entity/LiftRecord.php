<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use DateTime;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=LiftRecordRepository::class)
 */
class LiftRecord implements JsonSerializable
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
     */
    private ?int $weight = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $wodId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $exercise;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $repScheme;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $comment;

    public function __construct(int $wodId, string $exercise, string $repScheme, ?string $comment = null, ?int $weight = null)
    {
        // $exercise, $repScheme, $comment = '', $wodId
        // $this->id = $id;
        $this->repScheme = $repScheme;
        $this->exercise = $exercise;
        $this->weight = $weight ?? null;
        $this->comment = $comment;
        $this->wodId = $wodId;
        // if (!$id) {
            $this->createdAt = new DateTime();
        // }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWodId(): ?string
    {
        return $this->wodId;
    }

    public function getRepScheme(): ?string
    {
        return $this->repScheme;
    }

    public function getExercise(): ?string
    {
        return $this->exercise;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'weight' => $this->getWeight(),
            'comment' => $this->getComment(),
            'rep_scheme' => $this->getRepScheme(),
            'exercise' => $this->getExercise(),
        ];
    }
}
