<?php

namespace App\Application\Handler;

use App\Application\Command\CreateWod;
use App\Entity\Wod;
use App\Repository\WodRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

Class CreateWodHandler implements MessageHandlerInterface
{
	private WodRepository $wodRepository;
	private EntityManagerInterface $entityManager;

	public function __construct(EntityManagerInterface $entityManager, WodRepository $wodRepository)
	{
		$this->wodRepository = $wodRepository;
		$this->entityManager = $entityManager;
	}

	public function __invoke(CreateWod $createWod)
	{
		// dump($createWod);

		$wod = new Wod(
			$createWod->getWod(), 
			$createWod->getType(),
			$createWod->getWodDate(),
		);
				
		$this->entityManager->persist($wod);
		$this->entityManager->flush();

		// todo
		// $this->wodRepository->add($wod);
		// add using repo

	}
}