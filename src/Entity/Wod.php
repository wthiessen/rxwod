<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WodRepository")
 */
class Wod implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private ?string $wod;

    /**
     * @ORM\Column(type="string")
     */
    private ?string $type;

    /**
    * @var Collection|Score[]
    * @ORM\OneToMany(targetEntity="Score", mappedBy="wod", cascade={"persist", "remove"}, orphanRemoval=true)
    */
    private $scores;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $wodDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedOn;

    // todo add static create
    // todo move to domain layer

    public function __construct(?string $wod, ?string $type, ?string $wodDate)
    {
        $this->type = $this->getWodTypeFromWodString($wod) ?? 'Time';
        $this->wod = $wod;
        $this->wodDate = new DateTime($wodDate);
        $this->createdAt = new DateTime();
        $this->scores = new ArrayCollection();
    }

    public function update(?string $wod, ?string $type, ?string $wodDate)
    {
        $this->type = $type;
        $this->wod = $wod;
        $this->wodDate = new DateTime($wodDate);
        $this->updatedOn = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getWod(): ?string
    {
        return nl2br($this->wod);
    }
	
    public function getClassAsArray(): ?array
    {
        $input = $this->wod;

        $results = preg_split("/(\r\n|\n|\r)/",$input);
        // dd($results);
        if (count($results) == 1) {
            $results = explode("<br />",$input);
        }

        if (count($results) == 4) {
            unset($results[0]);
        }

        $wod = [];
        $i = 0;

        foreach ($results as $line) {
            // and next line starts with a number ie 1.
            if ($line === '') {
                $i++; // if the line is empty, advance array index
                continue;
            }

            $wod[$i][] = trim($line);
        }

        return $wod;
    }
	
	public function getWodBr(): ?string
	{
		return $this->wod;
	}

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedOn(): ?DateTime
    {
        return $this->updatedOn;
    }

    public function getWodDate(): ?DateTime
    {
        return $this->wodDate ?? $this->createdAt;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    /** @return Collection|Wod[] */
    public function getScores(): iterable
    {
        return $this->scores;
    }

    private function getWodTypeFromWodString($wod): ?string
    {
        $temp = explode('Daily Task', $wod);

        if (count($temp) > 1) {
            $array = preg_split ('/$\R?^/m', $temp[1]);

            foreach ($array as $a) {
                if ($a[0] == 'E' && strstr($a, 'M')) {
                    return 'EMOM';
                }
                if (strstr($a, 'AMRAP')) {
                    return 'AMRAP';
                }
                if (strstr($a, 'Time')) {
                    return 'Time';
                }
            }
        }

        return null;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'wod' => $this->wod,
            'type' => $this->type,
            'scores' => $this->scores->toArray(),
            'wodDate' => $this->getWodDate() ? $this->getWodDate()->format('Y-m-d') : null,
            'createdAt' => $this->getCreatedAt() ? $this->getCreatedAt()->format('Y-m-d') : null,
            'updatedOn' => $this->getUpdatedOn() ? $this->getUpdatedOn()->format('Y-m-d') : null,
            'class' => $this->getClassAsArray(),
        ];
    }
}
