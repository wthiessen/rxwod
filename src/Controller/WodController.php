<?php

namespace App\Controller;

use App\Entity\Exercise;
use App\Entity\Leaderboard;
use App\Entity\LiftRecord;
use App\Entity\Wod;
use App\Repository\ExerciseRepository;
use App\Repository\ExerciseTypeRepository;
use App\Repository\LeaderboardRepository;
use App\Repository\LiftRecordRepository;
use App\Repository\UserRepository;
use App\Repository\WodRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
//use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use DateTimeImmutable;
use DateInterval;
use PhpParser\Builder\Method;

class WodController extends AbstractController
{
    /**
      * @Route("/wod", name="wodlist")
     */
    public function index(ExerciseRepository $exerciseRepository, WodRepository $wodRepository)
    {
        return $this->render('wod/index.html.twig', [
            'wods' => [],
        ]);
    }

    /**
      * @Route("/wodnew", name="wod_angular")
     */
    public function wodnew(ExerciseRepository $exerciseRepository, WodRepository $wodRepository)
    {
        return $this->render('wod/index.html', [
//            'wods' => [],
        ]);
    }

    /**
      * @Route("wod/{id<\d+>}", name="app_wod_show")
     */
    public function show($id, WodRepository $wodRepository, LeaderboardRepository $leaderboardRepository, LiftRecordRepository $liftRecordRepository)
    {
        /** @var Wod|null $wod */
        $wod = $wodRepository->find($id);

        /** @var Leaderboard|null $leaderboard */
        $userNames = $leaderboardRepository->findAllNames();

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
    public function edit($id, WodRepository $wodRepository, LeaderboardRepository $leaderboardRepository)
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

    /**
    * @Route("wod/import", name="app_wod_import")
     */
    public function import(EntityManagerInterface $entityManager, WodRepository $wodRepository, LiftRecordRepository $liftRecordRepository, Request $request)
    {
        if ($request->isMethod('post')) {
            $createdAt = $request->request->get('createdAt');
            $wods = $request->request->get('wod');

//            $textArray = explode("Matt", $wods);
            $textArray = explode("<b>", $wods);
//            }

unset($textArray[0]);
$textArray = array_values($textArray);
unset($textArray[5]);
            $dates = array();
            $weekdays = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday');

            $wods = [];
            $date = $createdAt;
//echo '<pre>';
//dd($textArray);
//echo '</pre>';
//die;
            foreach ($textArray as $key => $wod) {
                foreach ($weekdays as $wd_key => $weekday) {
                    $wod = str_replace('</b>', '', $wod);
                    $wods[$weekdays[$key]] = $wod;
                }
            }

            $dateimm = '';
            $last_date = '';
            $date = date('Y-m-d', strtotime($date. '-1 day'));

            foreach ($wods as $day => $wod) {
                $wod = explode('<br/><br/>', $wod);

                $date = date('Y-m-d', strtotime($date.'+1 day'));
                $dateimm = new DateTimeImmutable($date);
                $temp = [];

                foreach ($wod as $w) {
                    if (!empty($w)) {
                        $temp[] = $w;
                    }

                    // if contains "Lift", put in lift records
                    if (strstr($w, 'Lift')) {
                        $w = trim($w);

                        $lift = str_replace('Lift', '', $w);

                        $lift = explode('<br/>', $w);

                        $exercise = $lift[1];
                        $rep_scheme = $lift[2];
                        $comment = $lift[3];

//                        echo '<pre>';
//                        var_dump($exercise,$rep_scheme,$comment);
//                        echo '</pre>';

                        // TODO find wodId. Find same date (created_at)

                        $newLiftRecord = new LiftRecord();
                        $newLiftRecord->setExercise($exercise)
                            ->setRepScheme($rep_scheme)
                            ->setComment($comment)
                            ->setCreatedAt($dateimm);

                        $entityManager->persist($newLiftRecord);
                        $entityManager->flush();
                    }
                }
                $wod = $temp;
//echo '<pre>';
//var_dump($wod);
//echo '</pre>';
//die;
                $newWod = new Wod();

//                    $newWods[] =
//                $newLiftRecord = new LiftRecord();

//                $wod = implode('\n', $wod);
//                $wod = json_encode($wod);

                $newWod->setWod($wod)
                    ->setCreatedAt($dateimm);

                $entityManager->persist($newWod);
                $entityManager->flush();
            }
            echo 'done';
//die;
            $this->redirectToRoute('wodlist');
        }

        return $this->render('wod/import.html.twig', [
            'secret' => $_ENV['GLOFOX_TOKEN'],
            'glofox_url' => $_ENV['GLOFOX_URL'],
        ]);

    }
}
