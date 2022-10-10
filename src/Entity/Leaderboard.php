<?php

namespace App\Entity;

use App\Repository\LeaderboardRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use App\Repository\WodRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"leaderboard:read"}},
 *     denormalizationContext={"groups"={"leaderboard:write"}},
 * )
 * @ORM\Entity(repositoryClass=LeaderboardRepository::class)
 * @ApiFilter(SearchFilter::class, properties={"wod": "exact", "dateCreated": "partial"})
 */
// TODO Change to score, add User if used ever
class Leaderboard
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"leaderboard:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"leaderboard:read", "leaderboard:write"})*
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"leaderboard:read", "leaderboard:write"})
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=2,
     *     max=50,
     *     maxMessage="Describe your Score in 50 chars or less"
     * )
     */
    private $score;

    /**
     * @ORM\Column(type="integer", length=10)
     * @Groups({"leaderboard:read", "leaderboard:write"})*
     * @Assert\NotBlank()
     */
    private $wod;

    /**
     * @return mixed
     */
    public function getWod()
    {
        return $this->wod;
    }

    /**
     * @param mixed $wod
     */
    public function setWod($wod): void
    {
        $this->wod = $wod;
    }

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"leaderboard:read", "leaderboard:write"})*
     */
    private $dateCreated;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"leaderboard:read", "leaderboard:write"})*
     */
    private $comments;

    /**
     * @ORM\Column(type="integer", length=10)
     * @Groups({"leaderboard:read", "leaderboard:write"})*
     */
    private $rx;

    public function __construct()
    {
        $this->dateCreated = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDateCreated(): ?DateTimeImmutable
    {
        return $this->dateCreated;
    }

    public function setDateCreated(DateTimeImmutable $dateCreated): self
    {
        $this->$dateCreated = $dateCreated;

        return $this;
    }

    public function getScore(): ?string
    {
        return $this->score;
    }

    public function setScore(string $score): self
    {
        $this->score = $score;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     */
    public function setComments($comments): void
    {
        $this->comments = $comments;
    }

    /**
     * @param mixed $rx
     */
    public function setRx($rx): void
    {
        $this->rx = $rx;
    }

    /**
     * @return mixed
     */
    public function getRx()
    {
        return $this->rx;
    }
}
