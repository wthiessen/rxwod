<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class WodType
{
    const TIME = 'Time';
    const EMOM = 'EMOM';
    const AMRAP = 'AMRAP';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    private ?array $types;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypes(): ?array
    {
        return [
            0 => self::TIME,
            1 => self::EMOM,
            2 => self::AMRAP,
        ];
    }
}
