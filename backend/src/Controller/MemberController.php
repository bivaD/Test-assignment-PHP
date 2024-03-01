<?php

namespace App\Controller;

use App\Repository\MemberRepository;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MemberController extends AbstractController
{
    private $memberRepository;
    private $gameRepository;

    public function __construct(MemberRepository $memberRepository, GameRepository $gameRepository)
    {
        $this->memberRepository = $memberRepository;
        $this->gameRepository = $gameRepository;
    }

    #[Route('/members', name: 'get_members', methods: ['GET'])]
    public function getMembers(): Response
    {
        $members = $this->memberRepository->findAllMembers();

        usort($members, function ($a, $b) {
            return $a->getJoinDate() <=> $b->getJoinDate();
        });

        $data = array();

        foreach ($members as $member) {
            $memberToSerialize = [
                'id' => $member->getId(),
                'name' => $member->getName(),
                'email' => $member->getEmail(),
                'joinDate' => $member->getJoinDate()
            ];
            array_push($data, $memberToSerialize);
        }
        return $this->json($data);
    }


    #[Route('/members/{id}', name: 'get_member', methods: ['GET'])]
    public function getMember(int $id): Response
    {
        $member = $this->memberRepository->find($id);
        if (!$member) {
            return new Response('Member not found', Response::HTTP_NOT_FOUND);
        }

        $data = [
            'name' => $member->getName(),
            'email' => $member->getEmail()
        ];

        $wins = $this->gameRepository->winsCountByMember($member->getId());
        $losses = $this->gameRepository->lossesCountByMember($member->getId());

        // win ratios
        $totalGamesWithWhite = $this->gameRepository->getWhiteGameCountByMember($member->getId());
        $wonGamesWithWhite = $this->gameRepository->getWhiteWinCountByMember($member->getId());

        $winPercentageWithWhite = $totalGamesWithWhite > 0 ? ($wonGamesWithWhite / $totalGamesWithWhite) * 100 : 0;

        $totalGamesWithBlack = $this->gameRepository->getBlackGameCountByMember($member->getId());
        $wonGamesWithBlack = $this->gameRepository->getBlackWinCountByMember($member->getId());

        $winPercentageWithBlack = $totalGamesWithBlack > 0 ? ($wonGamesWithBlack / $totalGamesWithBlack) * 100 : 0;

        // best game     
        $shortestGame = $this->gameRepository->findShortestWonGameByMember($member->getId());
        $shortestGameMoves = $shortestGame === null ? 0 : $shortestGame->getMoves();

        // other data
        $data['wins'] = $wins;
        $data['losses'] = $losses;
        $data['win_percentage_with_white'] = $winPercentageWithWhite;
        $data['win_percentage_with_black'] = $winPercentageWithBlack;
        $data['shortest_game'] = $shortestGameMoves;

        return $this->json($data);
    }

    #[Route('/members', name: 'add_member', methods: ['POST'])]
    public function addMember(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $name = $data['name'] ?? null;
        $email = $data['email'] ?? null;
        $joinDate = new \DateTime($data['join_date'] ?? 'now');

        $newMember = $this->memberRepository->createMember($name, $email, $joinDate);

        if ($newMember === null) {
            return new Response("Failed to create member (probably member with this email already exists).", Response::HTTP_BAD_REQUEST);
        }

        return new Response("New member created with ID: " . $newMember->getId(), Response::HTTP_CREATED);
    }

    #[Route('/members/{id}', name: 'update_member', methods: ['PUT'])]
    public function updateMember(int $id, Request $request): Response
    {
        $member = $this->memberRepository->find($id);
        if (!$member) {
            return new Response('Member not found', Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        if (isset($data['name'])) {
            $member->setName($data['name']);
        }
        if (isset($data['email'])) {
            $member->setEmail($data['email']);
        }
        if (isset($data['join_date'])) {
            $member->setJoinDate(new \DateTime($data['join_date']));
        }

        $this->memberRepository->updateMember($member);

        return new Response('Member updated', Response::HTTP_OK);
    }

    #[Route('/members/{id}', name: 'delete_member', methods: ['DELETE'])]
    public function deleteMember(int $id): Response
    {
        $member = $this->memberRepository->find($id);
        if (!$member) {
            return new Response('Member not found', Response::HTTP_NOT_FOUND);
        }

        $this->memberRepository->deleteMember($member);

        return new Response('Member deleted', Response::HTTP_OK);
    }
}
