<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\Member;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 *
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    // Create
    public function createGame(?string $winnerColor, int $moves, \DateTimeInterface $date, Member $white, Member $black): Game
    {
        $game = new Game();
        $game->setWinnerColor($winnerColor)
            ->setMoves($moves)
            ->setDate($date)
            ->setWhite($white)
            ->setBlack($black);

        $this->getEntityManager()->persist($game);
        $this->getEntityManager()->flush();

        return $game;
    }

    // Read
    public function findGameById(int $id): ?Game
    {
        $game = $this->find($id);
        return $game;
    }

    public function findAllGames(): array
    {
        return $this->findAll();
    }

    // Update
    public function updateGame(Game $game): Game
    {
        $this->getEntityManager()->persist($game);
        $this->getEntityManager()->flush();

        return $game;
    }

    // Delete
    public function deleteGame(Game $game): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($game);
        $entityManager->flush();
    }



    public function findShortestWonGameByMember(int $memberId): ?Game
    {
        return $this->createQueryBuilder('g')
            ->join('g.white', 'w')
            ->join('g.black', 'b')
            ->where('(g.white = :memberId and g.winner_color = :whiteColor) or (g.black = :memberId and g.winner_color = :blackColor)')
            ->setParameter('memberId', $memberId)
            ->setParameter('whiteColor', 'white')
            ->setParameter('blackColor', 'black')
            ->orderBy('g.moves', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    //others
    public function getWhiteGameCountByMember(int $memberId): int
    {
        return $this->createQueryBuilder('g')
            ->select('COUNT(g.id)')
            ->where('g.white = :memberId')
            ->setParameter('memberId', $memberId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getBlackGameCountByMember(int $memberId): int
    {
        return $this->createQueryBuilder('g')
            ->select('COUNT(g.id)')
            ->where('g.black = :memberId')
            ->setParameter('memberId', $memberId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getWhiteWinCountByMember(int $memberId): int
    {
        return $this->createQueryBuilder('g')
            ->select('COUNT(g.id)')
            ->where('g.white = :memberId')
            ->andWhere('g.winner_color = :color')
            ->setParameter('memberId', $memberId)
            ->setParameter('color', 'white')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getBlackWinCountByMember(int $memberId): int
    {
        return $this->createQueryBuilder('g')
            ->select('COUNT(g.id)')
            ->where('g.black = :memberId')
            ->andWhere('g.winner_color = :color')
            ->setParameter('memberId', $memberId)
            ->setParameter('color', 'black')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function winsCountByMember(int $memberId): int
    {
        return $this->getWhiteWinCountByMember($memberId) + $this->getBlackWinCountByMember($memberId);
    }

    public function lossesCountByMember(int $memberId): int
    {
        return $this->getWhiteGameCountByMember($memberId) + $this->getBlackGameCountByMember($memberId) - $this->winsCountByMember($memberId);
    }

    public function findLongestGame(): ?Game
    {
        return $this->createQueryBuilder('g')
            ->join('g.white', 'w', 'WITH', 'w.deleted = false')
            ->join('g.black', 'b', 'WITH', 'b.deleted = false')
            ->orderBy('g.moves', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findShortestGame(): ?Game
    {
        return $this->createQueryBuilder('g')
            ->join('g.white', 'w', 'WITH', 'w.deleted = false')
            ->join('g.black', 'b', 'WITH', 'b.deleted = false')
            ->orderBy('g.moves', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
