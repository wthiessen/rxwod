<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
// TODO Change to score, add User if used ever
class Score implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;
        
    /**
    * @ORM\ManyToOne(targetEntity="User", inversedBy="score")
    */
    private User $user;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=2,
     *     max=50,
     *     maxMessage="Describe your Score in 50 chars or less"
     * )
     */
    private $score;

    /**
     * @ORM\ManyToOne(targetEntity="Wod", inversedBy="scores")
     * @ORM\JoinColumn(name="wod", referencedColumnName="id")
     */
    private Wod $wod;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreated;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $comments;

    /**
     * @ORM\Column(type="integer", length=10)
     */
    private $rx;

    public function __construct(string $score, User $user, Wod $wod, string $comments)
    {
        $this->score = $score;
        $this->user = $user;
        $this->wod = $wod;
        $this->comments = $comments;
        $this->dateCreated = new DateTime();
    }

    public function update(string $score, User $user, Wod $wod, string $comments)
    {
        $this->score = $score;
        $this->user = $user;
        $this->wod = $wod;
        $this->comments = $comments;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDateCreated(): ?DateTime
    {
        return $this->dateCreated;
    }

    public function getScore()
    {
        return json_decode($this->score);
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function getRx()
    {
        return $this->rx;
    }

    public function getWod(): ?Wod
    {
        return $this->wod;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'wod_id' => $this->getWod()->getId(),
            'score' => $this->getScore(),
            'user_id' => $this->getUser()->getId(),
            // 'user' => $this->getUser(),
            'comments' => $this->getComments(),
        ];
    }
}
