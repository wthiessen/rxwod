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

        if (!$wod) {
            $this->redirectToRoute('wod_list');
        }

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

    /**
    * @Route("wod/import", name="app_wod_import")
     */
    public function import()
    {

        $text = '
Effort(Monday)
1. Warm Up
Stretch
3rds
8 Snatch Grip Deadlift 45/35
8 Snatch Grip Bent Over Row
8 OHS

2. Barbell Conditioning 
Emom x 7min
5 Power Snatch 75/55+4 Reverse OH Lunge+3 OHS 95/65

3. Daily Task 
Open WOD 15.4
AMRAP x8
3 HSPU
3 Clean 185/125
6/3,9/3, 12/6,15/6,18/6,21/9,24/9,27/9…..

Force(Tuesday)
1.Warm Up
Stretch
In 6min build to working weight for back squats 

2. Lift
Back Squat Clusters 
4rds w/2min rest
2+2 w/245/185

3.Daily Task
For Time w/25min cap
1,2,3…12
Deadlift 225/155
T2B
Bar Facing Burpee

Hi-Performance(Wednesday)
1. Warm Up Stretch
3rds
100m Run
8 Sprawls
8 Superman 

2. Skill
Gymnastics Protocol-Inverted 
Lvl 1
4 Sets w/1min rest
Half Moon Walk+8 Strict Press 

Lvl 2
4 Sets w/1min rest
16 Alt Z Press 50/35+3 Wall Walks

Lvl 3
4 Sets w/1min rest *reps unbroken 
10 Plate Walks+6 Tempo Push Press 135/95( 3 sec down)

3. Daily Task
For Time
15-10-5-10-15
Cal Row/Ski
S2O 115/85
Cal Row/Ski
C2B 

Utilization(Thursday)
1.Warm Up
Stretch

2.Aerobic Capacity
E2M10M
400m Row/Ski

3. Daily Task
2rds
10 Power Snatch 115/85
50 Double Unders
10 Thrusters 
50 Double Unders 

Revive(Friday)
1.Warm Up
Stretch
4min Bike/Row/Run
3rds
10 Kang Squat 45/35
8 Strict Press
10 Front Rack Lunge 

2.Gymnastic Protocol-Aerial
Lvl 1
4 Sets rest 1min
10 Db Floor Press+8 Ring Row+10-20 sec dead hang

Lvl 2
4 Sets rest 1min
5 Beat Swings+3 T2B+5 Pull Ups

Lvl 3
4 Sets Rest 1min
Muscle Up Complex
3 Ring/ Bar Muscle Ups+2 Strict Pull Ups+8 T2R/B

3.Daily Task
E4M24M
15 Cal Row/Ski
10 Squat Clean 135/95
15 Cal Row/Ski
        ';

        $text = trim($text);
        $textArray = explode("\n", $text);

        $dates = array();
        $weekdays = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
        $currentWeekday = '';
        $currentArray = array();
        $arrayItems = [];


        foreach ($textArray as $line) {
            foreach ($weekdays as $weekday) {
                if (strstr($line, $weekday)) {
                    if (!isset($dates[$weekday])) {
                        $dates[$weekday] = $line;
                    }
                } else {
                    if (!isset($dates[$weekday])) {
                        $dates[$weekday] = $line;
                    }
                }


//                    echo '<pre>';
//                    var_dump($line);
//                    echo '<pre>';
    //                $arrayItems['Monday'] = $line;
            }

//            $arrayItems[] .= '\n'.$line;
        }

//        return $dates;

echo '<pre>';
var_dump($dates);
echo '<pre>';
die;
//        return $dates;
    }



//        return $this->render('wod/import.html.twig', [
////            'wod' => $wod,
////            'leaderboard' => $leaderboard,
//        ]);

//    }
}
