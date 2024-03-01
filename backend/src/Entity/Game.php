<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $winner_color = null;

    #[ORM\Column]
    private ?int $moves = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'white_games')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Member $white = null;

    #[ORM\ManyToOne(inversedBy: 'BlackGame')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Member $black = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWinnerColor(): ?string
    {
        return $this->winner_color;
    }

    public function setWinnerColor(?string $winner_color): static
    {
        $this->winner_color = $winner_color;

        return $this;
    }

    public function getMoves(): ?int
    {
        return $this->moves;
    }

    public function setMoves(int $moves): static
    {
        $this->moves = $moves;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }


    public function getWhite(): ?Member
    {
        return $this->white;
    }

    public function setWhite(?Member $white): static
    {
        $this->white = $white;

        return $this;
    }

    public function getBlack(): ?Member
    {
        return $this->black;
    }

    public function setBlack(?Member $black): static
    {
        $this->black = $black;

        return $this;
    }
}
