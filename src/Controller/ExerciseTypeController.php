<?php

namespace App\Controller;

use App\Entity\Exercise;
use App\Entity\ExerciseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExerciseTypeController extends AbstractController
{
    /**
     * @Route("/exercise/type", name="exercise_type")
     */
    public function index(EntityManagerInterface $entityManager)
    {
        $repository = $entityManager->getRepository(ExerciseType::class);

        $exerciseTypes = $repository->findAll();

//        dd($exerciseTypes);
        return $this->render('exercise_type/index.html.twig', [
            'exerciseTypes' => $exerciseTypes,
        ]);
    }

    /**
     * @Route("/exercise/type/{id<\d+>}", name="app_exercise_type_show")
     */
    public function show($id, EntityManagerInterface $entityManager)
    {
        $repository = $entityManager->getRepository(ExerciseType::class);

        /** @var ExerciseType|null $exerciseType */
        $exerciseType = $repository->find($id);
//dd($exerciseType);
        if (!$exerciseType) {
            throw $this->createNotFoundException(sprintf('no $exercise Type found for id "%s"', $id));
        }

//        dd($exercise);

        return $this->render('exercise_type/show.html.twig', [
            'exerciseType' => $exerciseType,
        ]);
    }

    /**
     * @Route("/exercise/type/new", name="app_new_exercise_type", methods="POST")
     */
    public function new(EntityManagerInterface $entityManager, Request $request)
    {
        $repository = $entityManager->getRepository(ExerciseType::class);

        $name = $request->request->get('name');

        /** @var ExerciseType|null $exerciseType */
        $exerciseType = $repository->findOneBy(['name' => $name]);

        if ($exerciseType) {
            throw $this->createNotFoundException(sprintf('Exercise type already exists for "%s"', $name));
        }
//        dd($exerciseType);

        $exerciseType = new ExerciseType();
        $exerciseType->setName($name);

        $entityManager->persist($exerciseType);
        $entityManager->flush();

        return $this->redirect('/exercise/type');
    }
}
