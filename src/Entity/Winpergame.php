<?php

namespace App\Entity;

use App\Repository\WinpergameRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WinpergameRepository::class)
 */
class Winpergame
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
    private $player_name;

    /**
     * @ORM\Column(type="integer")
     */
    private $bet_won;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayerName(): ?string
    {
        return $this->player_name;
    }

    public function setPlayerName(string $player_name): self
    {
        $this->player_name = $player_name;

        return $this;
    }

    public function getBetWon(): ?int
    {
        return $this->bet_won;
    }

    public function setBetWon(int $bet_won): self
    {
        $this->bet_won = $bet_won;

        return $this;
    }
}
