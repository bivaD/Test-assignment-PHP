<?php

namespace App\Controller;

use App\Repository\MemberRepository;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    private $memberRepository;
    private $gameRepository;

    public function __construct(MemberRepository $memberRepository, GameRepository $gameRepository)
    {
        $this->memberRepository = $memberRepository;
        $this->gameRepository = $gameRepository;
    }

    #[Route('/games/{id}', name: 'get_game', methods: ['GET'])]
    public function getGame(int $id): Response
    {
        $game = $this->gameRepository->findGameById($id);

        if (!$game) {
            return new Response('Game not found.', Response::HTTP_NOT_FOUND);
        }

        $gameToSerialize = [
            'id' => $game->getId(),
            'winnerColor' => $game->getWinnerColor(),
            'moves' =>  $game->getMoves(),
            'date' =>  $game->getDate(),
            'whiteId' =>  $game->getWhite()->getId(),
            'blackId' => $game->getBlack()->getId(),
        ];

        return $this->json($gameToSerialize);
    }

    #[Route('/games', name: 'get_games', methods: ['GET'])]
    public function getMembers(): Response
    {
        $games = $this->gameRepository->findAllGames();

        $gamesToSerialize = array();

        foreach ($games as $game) {
            $winner = 'draw';
            if ($game->getWinnerColor() == 'black') {
                $winner = $game->getBlack()->getName() . ' (black)';
            } elseif ($game->getWinnerColor() == 'white') {
                $winner = $game->getWhite()->getName() . ' (white)';
            }

            if ($game->getWhite()->isDeleted()) {
                $whiteName = $game->getWhite()->getName() . ' (deleted)';
            } else {
                $whiteName = $game->getWhite()->getName();
            }

            if ($game->getBlack()->isDeleted()) {
                $blackName = $game->getBlack()->getName() . ' (deleted)';
            } else {
                $blackName = $game->getBlack()->getName();
            }

            $gameToSerialize = [
                'id' => $game->getId(),
                'winner' => $winner,
                'moves' => $game->getMoves(),
                'date' =>  $game->getDate(),
                'whiteId' =>  $game->getWhite()->getId(),
                'whiteName' =>  $whiteName,
                'blackId' => $game->getBlack()->getId(),
                'blackName' => $blackName,
            ];
            array_push($gamesToSerialize, $gameToSerialize);
        }

        return $this->json($gamesToSerialize);
    }

    #[Route('/games', name: 'add_game', methods: ['POST'])]
    public function addGame(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $winnerColor =  $data['winner'] ?? null;
        $moves = $data['moves'] ?? null;
        $whiteId = $data['white'] ?? null;
        $blackId = $data['black'] ?? null;
        $date = new \DateTime('now');

        $white = $this->memberRepository->find($whiteId);
        $black = $this->memberRepository->find($blackId);

        if (!$white || !$black) {
            return new Response('One or both players do not exist. ' . $whiteId . ' wb ' . $blackId, Response::HTTP_BAD_REQUEST);
        }

        $newGame = $this->gameRepository->createGame($winnerColor, $moves, $date, $white, $black);

        return new Response('New game added with ID: ' . $newGame->getId(), Response::HTTP_CREATED);
    }

    #[Route('/games/{id}', name: 'delete_game', methods: ['DELETE'])]
    public function deleteGame(int $id): Response
    {
        $game = $this->gameRepository->findGameById($id);

        if (!$game) {
            return new Response('Game not found.', Response::HTTP_NOT_FOUND);
        }

        $this->gameRepository->deleteGame($game);

        return new Response('Game deleted successfully.', Response::HTTP_OK);
    }
}
