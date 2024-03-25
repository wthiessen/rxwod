<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

class AMRAP
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="integer")
     */
    private int $time;

    /**
     * @ORM\Column(type="integer")
     */
    private int $rounds;

    /**
     * @ORM\Column(type="integer")
     */
    private int $reps;

    public function __construct(int $time)
    {
        $this->time = $time;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTime(): int
    {
        return $this->time;
    }

    public function getRounds(): int
    {
        return $this->time;
    }

    public function getReps(): int
    {
        return $this->time;
    }
}
