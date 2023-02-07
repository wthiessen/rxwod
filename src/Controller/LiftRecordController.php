<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LiftRecordController extends AbstractController
{
    /**
     * @Route("/lift_records", name="lift_record")
     */
    public function index(): Response
    {
        return $this->render('lift_record/index.html.twig', [
            'controller_name' => 'LiftRecordController',
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
}
