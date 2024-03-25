<?php

namespace App\Controller;

use App\Entity\LiftRecord;
use App\Entity\Wod;
use App\Repository\LiftRecordRepository;
use App\Repository\WodRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LiftRecordController extends AbstractController
{
    /**
     * @Route("/lift_records", name="lift_records")
     */
    public function index(LiftRecordRepository $liftRecordRepository): Response
    {
        /** @var LiftRecord|null $wod */
        $lift_records = $liftRecordRepository->findBy(array(),array('createdAt' => 'DESC'));

        return $this->render('lift_record/index.html.twig', [
        ]);
    }

    /**
      * @Route("/api2/lift_records/{wodId<\d+>}", methods={"GET"})
     */
    public function getLiftRecordByWod($wodId, LiftRecordRepository $liftRecordRepository): JsonResponse
    {
        // dd($wodId); 
        /** @var LiftRecord|null $wod */
        $liftRecord = $liftRecordRepository->findLiftRecordsByWod($wodId);

        return new JsonResponse($liftRecord);
    }    

    /**
      * @Route("/api2/lift_records", methods={"GET"})
     */
    public function getAllLiftRecords(LiftRecordRepository $liftRecordRepository): iterable
    {
        /** @var LiftRecord|null $liftRecords */
        $liftRecords = $liftRecordRepository->findAllWods();

        return new JsonResponse($liftRecords);
    }

    /**
     * @Route("/api2/lift_records/{id<\d+>}", methods={"PUT"})
     */
    public function update(int $id, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $liftRecord = new LiftRecord(
            $data['wod_id'],
            $data['exercise'],
            $data['rep_scheme'],
            $data['comment'],
            $data['weight']
        );

        $entityManager->persist($liftRecord);
        $entityManager->flush();

        return $this->json([]);
    }

    /**
     * @Route("/api2/lift_records", methods={"POST"})
     */
    public function add(Request $request, LiftRecordRepository $liftRecordRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $liftRecord = new LiftRecord(
            $data['wod_id'],
            $data['exercise'],
            $data['rep_scheme'],
            $data['comment'],
            $data['weight']
        );

        $entityManager->persist($liftRecord);
        $entityManager->flush();

        return $this->json([]);
    }    

    /**
     * @Route("lift_records/edit/{id<\d+>}", name="app_lift_edit")
     */
    public function edit($id, LiftRecordRepository $liftRecordRepository)
    {
        /** @var LiftRecord|null $wod */
        $lift_record = $liftRecordRepository->find($id);

        return $this->render('lift_record/edit.html.twig', [
            'lift_record' => $lift_record,
        ]);
    }

    /**
     * @Route("/lift_records/view", name="lift_record_new")
     */
    public function show(): Response
    {
        return $this->render('lift_record/edit.html.twig', [
            'controller_name' => 'LiftRecordController',
        ]);
    }

    /**
     * @Route("/lift_records/import/{id<\d+>}", name="lift_record_import")
     */
    public function import($id, Request $request, WodRepository $wodRepository, LiftRecordRepository $liftRecordRepository, EntityManagerInterface $entityManager): Response
    {
        /** @var Wod|null $wod */
        $wod = $wodRepository->find($id);

        $wod = $wod->getWodBr();
        
        $array = preg_split('/[0-9]\.\s+/', $wod);
        
        $lines = [];
        foreach ($array as $a) {
            if (strstr($a, 'Lift') || strstr($a, 'Cycle')) {
                $lines = explode('<br />', $a);
                if (count($lines) == 1) {
                    $lines = explode('<br/>', $a);
                }
             }
        }

        $exercises = ['Power Snatch', 'Deadlift', 'Power Clean', 'Front Squat', 'Back Squat', 'Push Jerk', 'Strict Press'];
        // Power Snatch +1 Hang Power Snatch

        $lift_exercise = '';
        $rep_scheme = '';
        $comment = [];

        foreach($lines as $line) {
            preg_match_all('/\w+/', $line, $match);

            if ($match[0]) {
                foreach ($exercises as $ex) {
                    if (strstr($line, $ex) && $lift_exercise == '') {
                        $lift_exercise = $ex;
                    }
                }
    
                $line = trim($line);

                preg_match_all('/^E[0-9].?[0-9]?M[0-9].?[0-9]?M$/', $line, $matches);
    
                if (!empty($matches[0])) {
                    $rep_scheme = $line;
                    continue;
                } else {
                    $rep_scheme = $line;
                }
    
                if (!strstr($line, 'Lift') || !strstr($line, 'Cycle')) {
                    // $comment[] = $line;
                }
            }
        }

        $comm = implode(',', $comment);
        $wodId = $id;

        $data = [
            'comment' => $comm,
            'exercise' => $lift_exercise,
        ];

        if (!$comm && !$lift_exercise && !$rep_scheme) {
            $data = [];
        }

        return $this->json($data);
    }
}
