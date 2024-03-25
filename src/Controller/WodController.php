<?php

namespace App\Controller;

use App\Application\Command\CreateWod;
use App\Entity\Score;
use App\Entity\LiftRecord;
use App\Entity\Wod;
use App\Repository\ScoreRepository;
use App\Repository\LiftRecordRepository;
use App\Repository\WodRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;

class WodController extends AbstractController
{
    /**
      * @Route("/wod", name="wod_list")
     */
    public function index()
    {
        return $this->render('wod/index.html.twig', [
            'wods' => [],
        ]);
    }


    /**
      * @Route("/api2/wod/{id<\d+>}", name="app_get_wod", methods={"GET"})
     */
    public function getWod($id, WodRepository $wodRepository)
    {
        /** @var Wod|null $wod */
        $wod = $wodRepository->find($id);

        return new JsonResponse($wod);
        // return [];
    }    

    /**
      * @Route("/api2/wods", name="app_get_all_wod", methods={"GET"})
     */
    public function getAllWods(WodRepository $wodRepository)
    {
        // /** @var Wod|null $wod */
        $wods = $wodRepository->findAllWods();
// dd($wods);
        // $wods = [];

        return new JsonResponse($wods);
    }    


    /**
      * @Route("wod/{id<\d+>}", name="app_wod_show", methods={"GET"})
     */
    public function show($id, WodRepository $wodRepository, ScoreRepository $scoreRepository, LiftRecordRepository $liftRecordRepository)
    {
        /** @var Wod|null $wod */
        $wod = $wodRepository->find($id);

        /** @var Score|null $score */
        $userNames = $scoreRepository->findAllNames();

        $names = [];
        foreach ($userNames as $name) {
            $names[] = $name['name'];
        }

        /** @var Wod|null $wod */
        $nextWod = $wodRepository->findOneNextId($wod->getId());

        /** @var LiftRecord|null $liftRecord */
        $liftRecord = $liftRecordRepository->findBy(['wodId' => $wod->getId()]);

        if ($liftRecord) {
            $liftRecord = $liftRecord[0];
        }

        /** @var array|null */
        $previousWod = $wodRepository->findOnePreviousId($wod->getId());

        return $this->render('wod/show.html.twig', [
            'wod' => $wod,
            'lift_record_id' => $liftRecord ? $liftRecord->getId() : null,
            'next_wod' => $nextWod ? $nextWod->getId() : null,
            'previous_wod' => end($previousWod) ? end($previousWod)->getId() : null,
            'userNames' => $names,
        ]);
    }

    /**
      * @Route("wod/edit/{id<\d+>}", name="app_wod_edit")
     */
    public function edit($id, WodRepository $wodRepository, ScoreRepository $scoreRepository)
    {
        /** @var Wod|null $wod */
        $wod = $wodRepository->find($id);

        /** @var Score|null $score */
        $score = $scoreRepository->findByWodId($id);

        return $this->render('wod/edit.html.twig', [
            'wod' => $wod,
            'leaderboard' => $score,
        ]);
    }

    /**
      * @Route("/api2/wod/{id<\d+>}", name="app_wod_editapi", methods={"PUT"})
     */
    public function editApi($id, Request $request, EntityManagerInterface $entityManager, WodRepository $wodRepository): JsonResponse
    {
        /** @var Wod|null $wod */
        $wod = $wodRepository->find($id);

        $data = json_decode($request->getContent(),true);

        // TODO add method to get Daily Task workout type (AMRAP, EMOM, Time, etc)


        $wod->update(
            $data['wod'],
            $data['type'],
            $data['wodDate'],
        );

        $entityManager->persist($wod);
        $entityManager->flush();
// dd($wod);
        return $this->json('ok');
    }

    /**
      * @Route("/api/wods/{id<\d+>}", methods={"DELETE"})
     */
    public function delete($id, Request $request, EntityManagerInterface $entityManager, WodRepository $wodRepository): JsonResponse
    {
        /** @var Wod|null $wod */
        $wod = $wodRepository->find($id);

        $entityManager->remove($wod);

        // $entityManager->persist($wod);
        $entityManager->flush();
// dd($wod);
        return $this->json('ok');
    }

    /**
      * @Route("/api2/wods", methods={"POST"})
     */
    public function addApi(Request $request, EntityManagerInterface $entityManager, MessageBusInterface $messageBus): JsonResponse
    {
        $data = json_decode($request->getContent(),true);

		$type = '';

        if (!isset($data['type'])) {
            $type = "Time";
        }

        $messageBus->dispatch(new CreateWod(
            $data['wod'], $type, $data['wodDate']
        ));

		return $this->json('ok added wod');
    }

    /**
    * @Route("wod/import", name="app_wod_import")
     */
    public function import(EntityManagerInterface $entityManager, WodRepository $wodRepository, LiftRecordRepository $liftRecordRepository, Request $request)
    {
        // $input = '<b>Hi-Performance(Wednesday)</b>
        // 1. Warm Up
        // 3rds
        // 6 PVC Pass Through 
        // 6 PVC OHS 
        // 6 PVC Sotts Press 
        // 6 PVC Duck Walk 
        // 6 PVC Backwards Duck Walk 
        
        // 2. Barbell Conditioning
        // E1.5M7.5
        // 5 TNG Power Clean &amp; Jerks 
        // 5 Lateral Burpee Over Bar 
        
        // 3. Daily Task
        // 6rds
        // 3 Power Snatch 115/85
        // 6 OHS
        // 9 C2B';
        
        
        // $results = preg_split('/\s*\d+\.+\s+/i', $input);


        // $result = $matches[1];
        // dd($results);
        if ($request->isMethod('post')) {
            $createdAt = $request->request->get('createdAt');
            $wods = trim($request->request->get('wod'));

            // $wods = explode('The RX Gym Team', $wods);
            // $wods = explode('Matt', $wods);

            
            // if (!isset($wods[1])) {
                //     return $this->json('Failed Import');
                // }
                

            $textArray = explode("<b>", $wods);
            // $textArray = explode("<strong>", $wods[1]);
//            }
// dd($textArray);
            unset($textArray[0]);
            $textArray = array_values($textArray);
            unset($textArray[5]);
            $weekdays = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
            $wods = [];
            $date = $createdAt;

            foreach ($textArray as $key => $wod) {


                foreach ($weekdays as $wd_key => $weekday) {
                    if (!strpos($wod, $weekday)) {
                        continue;
                    }

                    $wod = str_replace('</strong>', '', $wod);


                    $wods[$weekdays[$key]]['wod'] = $wod;
                }
            }


            $dateimm = '';
            $last_date = '';
            $date = date('Y-m-d', strtotime($date.'-1 day'));
            $newDate = $date;
            foreach ($wods as $day => $wod) {
                $newDate = date('Y-m-d', strtotime($newDate.'+1 day'));
                $newWod = new Wod($wod['wod'], $newDate);
// dump($newWod);
                $entityManager->persist($newWod);
                $entityManager->flush();
            }

            echo 'done';
            $this->redirectToRoute('wod_list');
        }

        return $this->render('wod/import.html.twig', [
            'secret' => $_ENV['GLOFOX_TOKEN'],
            'glofox_url' => $_ENV['GLOFOX_URL'],
        ]);
    }
}
