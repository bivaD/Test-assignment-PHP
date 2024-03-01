<?php

namespace App\Controller;

use App\Repository\MemberRepository;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LeaderboardController extends AbstractController
{
    private $memberRepository;
    private $gameRepository;

    public function __construct(MemberRepository $memberRepository, GameRepository $gameRepository)
    {
        $this->memberRepository = $memberRepository;
        $this->gameRepository = $gameRepository;
    }

    #[Route('/leaderboard/rank', name: 'get_rank', methods: ['GET'])]
    public function getRank(): Response
    {
        $members = $this->memberRepository->findAllMembers();

        $data = array();

        foreach ($members as $member) {

            $totalGamesWithWhite = $this->gameRepository->getWhiteGameCountByMember($member->getId());
            $totalGamesWithBlack = $this->gameRepository->getBlackGameCountByMember($member->getId());

            if ($totalGamesWithBlack < 10 || $totalGamesWithWhite < 10) {
                continue;
            }

            $wonGamesWithWhite = $this->gameRepository->getWhiteWinCountByMember($member->getId());
            $wonGamesWithBlack = $this->gameRepository->getBlackWinCountByMember($member->getId());

            $wonGames = $wonGamesWithBlack + $wonGamesWithWhite;

            $memberToSerialize = [
                'name' => $member->getName(),
                'email' => $member->getEmail(),
                'wonGames' => $wonGames,
            ];
            array_push($data, $memberToSerialize);
        }

        usort($data, function ($a, $b) {
            return $b['wonGames'] <=> $a['wonGames'];
        });

        $data = array_slice($data, 0, 10);

        return $this->json($data);
    }

    #[Route('/leaderboard/stats', name: 'get_stats', methods: ['GET'])]
    public function getStats(): Response
    {
        $longestGame = $this->gameRepository->findLongestGame();
        $shortestGame = $this->gameRepository->findShortestGame();

        $shortestGameToSerialize = [
            'moves' => $shortestGame->getMoves(),
            'winnerName' => $shortestGame->getWinnerColor() === 'black' ? $shortestGame->getBlack()->getName() : $shortestGame->getWhite()->getName(),
            'winnerEmail' => $shortestGame->getWinnerColor() === 'black' ? $shortestGame->getBlack()->getEmail() : $shortestGame->getWhite()->getEmail(),
            'loserName' => $shortestGame->getWinnerColor() !== 'black' ? $shortestGame->getBlack()->getName() : $shortestGame->getWhite()->getName(),
            'loserEmail' => $shortestGame->getWinnerColor() !== 'black' ? $shortestGame->getBlack()->getEmail() : $shortestGame->getWhite()->getEmail(),
            'date' => $shortestGame->getDate()
        ];

        $longestGameToSerialize = [
            'moves' => $longestGame->getMoves(),
            'winnerName' => $longestGame->getWinnerColor() === 'black' ? $longestGame->getBlack()->getName() : $longestGame->getWhite()->getName(),
            'winnerEmail' => $longestGame->getWinnerColor() === 'black' ? $longestGame->getBlack()->getEmail() : $longestGame->getWhite()->getEmail(),
            'loserName' => $longestGame->getWinnerColor() !== 'black' ? $longestGame->getBlack()->getName() : $longestGame->getWhite()->getName(),
            'loserEmail' => $longestGame->getWinnerColor() !== 'black' ? $longestGame->getBlack()->getEmail() : $longestGame->getWhite()->getEmail(),
            'date' => $longestGame->getDate()
        ];

        $data = [
            'shortestGame' => $shortestGameToSerialize,
            'longestGame' => $longestGameToSerialize,
        ];

        return $this->json($data);
    }
}
