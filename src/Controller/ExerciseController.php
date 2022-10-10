<?php

//Todo: delete

namespace App\Controller;

use App\Entity\Exercise;
use App\Entity\ExerciseType;
use App\Repository\ExerciseTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExerciseController extends AbstractController
{
    /**
     * @Route("/exercise", name="exercise")
     */

    public function homepage(EntityManagerInterface $entityManager, ExerciseTypeRepository $exerciseTypeRepository)
    {
        $repository = $entityManager->getRepository(Exercise::class);

        $exercises = $repository->findAll([], ['createdAt' => 'DESC']);

        $exerciseTypes = $exerciseTypeRepository->findAll();

        return $this->render('exercise/homepage.html.twig', [
            'exercises' => $exercises,
            'exerciseTypes' => $exerciseTypes
        ]);
    }

    /**
     * @Route("/exercise/new", name="app_new_exercise", methods="POST")
     */
    public function new(EntityManagerInterface $entityManager, ExerciseTypeRepository $typeRepository, Request $request)
    {
        $name = $request->request->get('name');
        $type_id = $request->request->get('type');

        $type = $typeRepository->find($type_id);

//        Todo: validate type, allow same name with different type? ie S2O barbell/db

        $exercise = new Exercise();
        $exercise->setName($name)
            ->setCreatedAt(new \DateTimeImmutable('now'));

        if ($type) {
            $exercise->setType($type);
            $entityManager->persist($type);
        }

        $entityManager->persist($exercise);
        $entityManager->flush();

        return $this->redirectToRoute('exercise');
    }
    /**
     * @Route("exercise/{id<\d+>}", name="app_exercise_show")
     */
    public function show($id, EntityManagerInterface $entityManager)
    {
        $repository = $entityManager->getRepository(Exercise::class);

        /** @var Exercise|null $exercise */
        $exercise = $repository->findOneBy(['id' => $id]);

        if (!$exercise) {
          throw $this->createNotFoundException(sprintf('no exercise found for id "%s"', $id));
        }

        return $this->render('exercise/show.html.twig', [
            'exercise' => $exercise,
        ]);
    }


}
