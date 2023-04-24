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
use Doctrine\ORM\EntityManager;
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
use DateTimeImmutable;
use DateInterval;

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
    public function show($id, WodRepository $wodRepository, LeaderboardRepository $leaderboardRepository, MarkdownParserInterface $markdownParser, EntityManagerInterface $em)
    {
        /** @var Wod|null $wod */
        $wod = $wodRepository->find($id);

        if (!$wod) {
            $this->redirectToRoute('wod_list');
        }

        /** @var Leaderboard|null $leaderboard */
        $userNames = $leaderboardRepository->findAllNames();

        $names = [];
        foreach ($userNames as $name) {
            $names[] = $name['name'];
        }

//        $date = $wod->getCreatedAt()->format('Y-m-d');
//
//        $next_date = date('Y-m-d', strtotime($date. '+1 day'));
//        echo $next_date; die;
//var_dump($next_date); die;
        /** @var Wod|null $wod */
        $nextWod = $wodRepository->findOneNextId($wod->getId());

        /** @var array|null */
        $previousWod = $wodRepository->findOnePreviousId($wod->getId());

//        $query = $em->createNativeQuery('SELECT id FROM wod WHERE
//        id = (SELECT id FROM users WHERE id > 2 LIMIT 1)', $rsm);
//
//        var_dump($nextwod); die;


//        var_dump(end($previousWod)->getId()); die;
//        var_dump($wod->getId()); die;

        return $this->render('wod/show.html.twig', [
            'wod' => $wod,
            'next_wod' => $nextWod ? $nextWod->getId() : null,
            'previous_wod' => end($previousWod) ? end($previousWod)->getId() : null,
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

    /**
    * @Route("wod/import", name="app_wod_import")
     */
    public function import(EntityManagerInterface $entityManager, WodRepository $wodRepository, Request $request)
    {
        if ($request->isMethod('post')) {
            $createdAt = $request->request->get('createdAt');
            $wods = $request->request->get('wod');

            $wods = trim($wods);
            $textArray = explode("\n", $wods);

            $dates = array();
            $weekdays = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday');
            $currentWeekday = '';
            $currentArray = array();
            $arrayItems = [];

            $current_day = '';
            $pattern = '/\((.*)\)/';
            $prev_day = '';
            $wods = [];
            $temp = '';

            $date = $createdAt;

            foreach ($textArray as $line) {
                if (preg_match($pattern, $line, $matches, PREG_OFFSET_CAPTURE)) {
                    if (in_array($matches[1][0], $weekdays)) {
                        $temp = '';
                        if (!isset($temp[$matches[1][0]])) {
                            $temp .= $line;

                            $current_day = $matches[1][0];
                        }

                    }
                } else {
                    $temp .= $line;
                }
                $wods[$current_day] = $temp;
            }

            $dateimm = '';

            $last_date = '';

            $date = date('Y-m-d', strtotime($date. '-1 day'));

            foreach ($wods as $day =>$wod) {
                $newWod = new Wod();

                $date = date('Y-m-d', strtotime($date.'+1 day'));

                $dateimm = new DateTimeImmutable($date);

                $newWod->setWod(trim($wod))
                    ->setCreatedAt($dateimm);

//                $last_date = strtotime($date);

                $entityManager->persist($newWod);
                $entityManager->flush();

            }

            $this->redirectToRoute('wod_list');
        }

        return $this->render('wod/import.html.twig', [
        ]);

    }
}
