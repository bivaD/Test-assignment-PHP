<?php

namespace App\Controller;

use App\Repository\MemberRepository;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GeneratorController extends AbstractController
{
    private $memberRepository;
    private $gameRepository;

    public function __construct(MemberRepository $memberRepository, GameRepository $gameRepository)
    {
        $this->memberRepository = $memberRepository;
        $this->gameRepository = $gameRepository;
    }


    #[Route('/generator/members', name: 'generate_members', methods: ['GET'])]
    public function generateMembers(): Response
    {
        $names = ['John', 'Emma', 'Michael', 'Sophia', 'William', 'Olivia', 'James', 'Ava', 'Alexander', 'Isabella'];
        $surnames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez'];


        for ($i = 0; $i < 10; $i++) {
            $randomName = $names[array_rand($names)];
            $randomSurname = $surnames[array_rand($surnames)];

            $randomEmail = mt_rand(100, 999) . '@email.com';

            $fullName = $randomName . ' ' . $randomSurname;


            $date = new \DateTime();
            $date->modify('-' . mt_rand(0, 365) . ' days');

            $this->memberRepository->createMember($fullName, $randomEmail, $date);
        }

        return new Response('generated successfully', Response::HTTP_CREATED);
    }

    #[Route('/generator/games', name: 'generate_games', methods: ['GET'])]
    public function generateGames(GameRepository $gameRepository, MemberRepository $memberRepository): Response
    {
        $undeletedMembers = $memberRepository->findAllMembers();

        if (count($undeletedMembers) < 2) {
            return new Response('Cannot generate games. There must be at least two undeleted members.', Response::HTTP_BAD_REQUEST);
        }

        for ($i = 0; $i < 30; $i++) {
            $white = $undeletedMembers[array_rand($undeletedMembers)];
            $black = $undeletedMembers[array_rand($undeletedMembers)];

            while ($white === $black) {
                $black = $undeletedMembers[array_rand($undeletedMembers)];
            }

            $moves = mt_rand(5, 120);

            $winnerColor = mt_rand(0, 1) == 0 ? 'black' : 'white';

            $date = new \DateTime();
            $date->modify('-' . mt_rand(0, 365) . ' days');

            $gameRepository->createGame($winnerColor, $moves, $date, $white, $black);
        }

        return new Response('generated successfully', Response::HTTP_CREATED);
    }
}
