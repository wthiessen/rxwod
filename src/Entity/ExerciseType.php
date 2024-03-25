<?php

namespace App\Entity;

use App\Repository\ExerciseTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
// repositoryClass=ExerciseTypeRepository::class
class ExerciseType
{
    CONST BARBELL = 'barbell';
    CONST DUMBELL = 'dumbbell';
    CONST GYMNASTICS = 'gymnastics';
    CONST CARDIO = 'cardio';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $type;

    public function __construct(string $name, string $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getTypes(): ?array
    {
        return [
            self::BARBELL,
            self::DUMBELL,
            self::GYMNASTICS,
            self::CARDIO,
        ];
    }
}
