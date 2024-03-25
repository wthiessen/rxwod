<?php

namespace App\Controller;

use App\Entity\WodType;
use App\Repository\WodTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class WodTypeController extends AbstractController
{
    /**
      * @Route("wod/types", name="app_wod_types")
     */
    public function getTypes(WodTypeRepository $wodTypeRepository): ?JsonResponse
    {
        // /** @var WodType|null $wodTypes */
        // $wodTypes = $wodTypeRepository->findAll();
        $wodTypes = new WodType;

        $types = $wodTypes->getTypes();

        return $this->json($types);
    }
}
