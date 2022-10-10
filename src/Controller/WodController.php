<?php

namespace App\Controller;

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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class WodController extends AbstractController
{
    /**
    //  * @Route("/wod", name="wod_list")
     */
    public function index(ExerciseRepository $exerciseRepository, WodRepository $wodRepository)
    {
        return $this->render('wod/index.html.twig', [
            'wods' => [],
        ]);
    }

    /**
    //  * @Route("wod/{id<\d+>}", name="app_wod_show")
     */
    public function show($id, WodRepository $wodRepository, LeaderboardRepository $leaderboardRepository, MarkdownParserInterface $markdownParser)
    {
        /** @var Wod|null $wod */
        $wod = $wodRepository->find($id);

        /** @var Leaderboard|null $leaderboard */
        $userNames = $leaderboardRepository->findAllNames();

        $names = [];
        foreach ($userNames as $name) {
            $names[] = $name['name'];
        }

        return $this->render('wod/show.html.twig', [
            'wod' => $wod,
            'userNames' => $names,
        ]);
    }

    /**
    //  * @Route("wod/edit/{id<\d+>}", name="app_wod_edit")
     */
    public function edit($id, WodRepository $wodRepository, LeaderboardRepository $leaderboardRepository, MarkdownParserInterface $markdownParser)
    {
        /** @var Wod|null $wod */
        $wod = $wodRepository->find($id);

        /** @var Leaderboard|null $leaderboard */
        $leaderboard = $leaderboardRepository->findByWodId($id);

        return $this->render('wod/edit.html.twig', [
            'wod' => $wod,
            'leaderboard' => $leaderboard,
        ]);
    }
}
