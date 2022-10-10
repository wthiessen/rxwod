<?php

namespace App\Controller;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Exercise;
use App\Entity\Leaderboard;
use App\Entity\Wod;
use App\Repository\ExerciseRepository;
use App\Repository\ExerciseTypeRepository;
use App\Repository\LeaderboardRepository;
use App\Repository\UserRepository;
use App\Repository\WodRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

// TODO show new leaderboard for date, join to wod on date?

class LeaderBoardController extends AbstractController
{
    /**
     * @Route("/leaderboard", name="leader_board")
     */
    public function index()
    {
        return $this->render('leader_board/index.html.twig', [
            'scores' => [],
        ]);
    }

    /**
      * @Route("leaderboards/{id<\d+>}", name="app_leaderboard_show")
      */
    public function show($id, LeaderboardRepository $leaderboardRepository, WodRepository $wodRepository, MarkdownParserInterface $markdownParser)
    {
        /** @var Leaderboard|null $leaderboard */
        $leaderboard = $leaderboardRepository->findBy(['id' =>$id, ['score' => 'ASC']]);

        /** @var Wod|null $wod */
        $wod = $wodRepository->find($leaderboard->getWod());

        return $this->render('leader_board/show.html.twig', [
            'leaderboard' => $leaderboard,
            'wod' => $wod,
        ]);
    }

    /**
    //  * @Route("leaderboards/edit/{id<\d+>}", name="app_leaderboard_edit")
     */
    public function edit($id, WodRepository $wodRepository, LeaderboardRepository $leaderboardRepository, MarkdownParserInterface $markdownParser)
    {
        /** @var Leaderboard|null $leaderboard */
        $leaderboard = $leaderboardRepository->find($id);
        $userNames = $leaderboardRepository->findAllNames();

        $names = [];
        foreach ($userNames as $name) {
            $names[] = $name['name'];
        }

        return $this->render('leader_board/edit.html.twig', [
            'leaderboard' => $leaderboard,
            'userNames' => $names,
        ]);
    }

}

