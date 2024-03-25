<?php

namespace App\Controller;

use App\Entity\Score;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Wod;
use App\Entity\User;
use App\Repository\ScoreRepository;
use App\Repository\UserRepository;
use App\Repository\WodRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

// TODO show new leaderboard for date, join to wod on date?

class ScoreController extends AbstractController
{
    private UserRepository $userRepository;
    private WodRepository $wodRepository;
    private ScoreRepository $scoreRepository;

    public function __construct(UserRepository $userRepository, WodRepository $wodRepository, ScoreRepository $scoreRepository)
    {
        $this->userRepository = $userRepository;
        $this->wodRepository = $wodRepository;
        $this->scoreRepository = $scoreRepository;
    }


    /**
     * @Route("/leaderboard", name="leader_board")
     */
    public function index(WodRepository $wodRepository)
    {
        $date = new \DateTimeImmutable(date('Y-m-d' ));

        $wod = $wodRepository->findOneBy(['createdAt'=> $date]);

        return $this->render('leader_board/index.html.twig', [
            'scores' => [],
            'wod_id' => $wod->getId() - 1,
        ]);
    }

    /**
      * @Route("leaderboards/{id<\d+>}", name="app_leaderboard_show")
      */
    public function show($id, WodRepository $wodRepository)
    {
//        /** @var Leaderboard|null $leaderboard */
//        $leaderboard = $scoreRepository->findBy(['id' =>$id, ['score' => 'ASC']]);
//
//        /** @var Wod|null $wod */
//        $wod = $wodRepository->find($leaderboard->getWod());

        return $this->render('leaderboards/index.html.twig', [
//            'scores' => [],
        ]);
    }

    /**
      * @Route("/api2/leaderboards/{id<\d+>}", methods={"GET"})
      */
    public function getAllByWod($id)
    {
       /** @var Score|null $score */
       $scores = $this->scoreRepository->findByWodId($id);

        return new JsonResponse($scores);
    }
        
    /**
      * @Route("/api2/leaderboards", methods={"POST"})
     */
    public function addApi(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(),true);

        /** @var User|null $user */
        $user = $this->userRepository->find($data['user_id']);

        /** @var Wod|null $wod */
        $wod = $this->wodRepository->find($data['wod_id']);

        if ($user && $wod) {
            $score = new Score($data['score'], $user, $wod, $data['comments']);

            $entityManager->persist($score);
            $entityManager->flush();

            return $this->json('ok added score');
        }

        return $this->json(false);
    }
    
    /**
     * @Route("/api2/leaderboards/{id<\d+>}", methods={"PUT"})
     */
    public function editApi(int $id, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        /** @var Score|null $score */
        $score = $this->scoreRepository->find($id);
        
        /** @var User|null $user */
        $user = $this->userRepository->find($data['user_id']);

        /** @var Wod|null $wod */
        $wod = $this->wodRepository->find($data['wod_id']);
        
        if ($user && $wod) {
            $score->update($data['score'], $user, $wod, $data['comments']);
            
            $entityManager->persist($score);
            $entityManager->flush();
            
            return $this->json('ok edited score');
        }
        
        return $this->json(false);
    }

    /**
     * @Route("leaderboards/edit/{id<\d+>}", name="app_leaderboard_edit")
     */
    public function edit($id, ScoreRepository $scoreRepository)
    {
        /** @var Score|null $leaderboard */
        $leaderboard = $scoreRepository->find($id);
        $userNames = $scoreRepository->findAllNames();

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

