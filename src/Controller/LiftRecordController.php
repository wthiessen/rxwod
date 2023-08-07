<?php

namespace App\Controller;

use App\Entity\Leaderboard;
use App\Entity\LiftRecord;
use App\Entity\Wod;
use App\Repository\LeaderboardRepository;
use App\Repository\LiftRecordRepository;
use App\Repository\WodRepository;
use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
//var_dump($lift_records);
        return $this->render('lift_record/index.html.twig', [
            'lift_records' => $lift_records,
        ]);
    }

    /**
     * @Route("/lift_records/add", name="lift_record_new")
     */
    public function show(): Response
    {
        return $this->render('lift_record/edit.html.twig', [
            'controller_name' => 'LiftRecordController',
        ]);
    }

    /**
     * @Route("lift_record/edit/{id<\d+>}", name="app_lift_edit")
     */
    public function edit($id, LiftRecordRepository $liftRecordRepository, MarkdownParserInterface $markdownParser)
    {
        /** @var LiftRecord|null $wod */
        $lift_record = $liftRecordRepository->find($id);

        return $this->render('lift_record/edit.html.twig', [
            'lift_record' => $lift_record,
        ]);
    }
}
