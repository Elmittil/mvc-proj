<?php

namespace App\Entity;

use App\Repository\DicestatisticRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DicestatisticRepository::class)
 */
class Dicestatistic
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $dice_value;

    /**
     * @ORM\Column(type="integer")
     */
    private $occurrence;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiceValue(): ?int
    {
        return $this->dice_value;
    }

    public function setDiceValue(int $dice_value): self
    {
        $this->dice_value = $dice_value;

        return $this;
    }

    public function getOccurrence(): ?int
    {
        return $this->occurrence;
    }

    public function setOccurrence(int $occurrence): self
    {
        $this->occurrence = $occurrence;

        return $this;
    }
}
