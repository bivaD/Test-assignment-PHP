<?php

namespace App\Entity;

use App\Repository\MemberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MemberRepository::class)]
#[ORM\Table(name: '`member`')]
class Member
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $join_date = null;

    #[ORM\Column]
    private ?bool $deleted = null;

    #[ORM\OneToMany(targetEntity: Game::class, mappedBy: 'white', orphanRemoval: true)]
    private Collection $white_games;

    #[ORM\OneToMany(targetEntity: Game::class, mappedBy: 'black', orphanRemoval: true)]
    private Collection $BlackGame;

    public function __construct()
    {
        $this->white_games = new ArrayCollection();
        $this->BlackGame = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getJoinDate(): ?\DateTimeInterface
    {
        return $this->join_date;
    }

    public function setJoinDate(\DateTimeInterface $join_date): static
    {
        $this->join_date = $join_date;

        return $this;
    }

    public function isDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): static
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * @return Collection<int, Game>
     */
    public function getWhiteGames(): Collection
    {
        return $this->white_games;
    }

    public function addWhiteGame(Game $whiteGame): static
    {
        if (!$this->white_games->contains($whiteGame)) {
            $this->white_games->add($whiteGame);
            $whiteGame->setWhite($this);
        }

        return $this;
    }

    public function removeWhiteGame(Game $whiteGame): static
    {
        if ($this->white_games->removeElement($whiteGame)) {
            // set the owning side to null (unless already changed)
            if ($whiteGame->getWhite() === $this) {
                $whiteGame->setWhite(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Game>
     */
    public function getBlackGame(): Collection
    {
        return $this->BlackGame;
    }

    public function addBlackGame(Game $blackGame): static
    {
        if (!$this->BlackGame->contains($blackGame)) {
            $this->BlackGame->add($blackGame);
            $blackGame->setBlack($this);
        }

        return $this;
    }

    public function removeBlackGame(Game $blackGame): static
    {
        if ($this->BlackGame->removeElement($blackGame)) {
            // set the owning side to null (unless already changed)
            if ($blackGame->getBlack() === $this) {
                $blackGame->setBlack(null);
            }
        }

        return $this;
    }
}
