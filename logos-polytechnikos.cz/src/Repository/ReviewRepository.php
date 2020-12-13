<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\ReviewState;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class ReviewRepository
{

	/** @var ManagerRegistry */
	private $em;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->em = $entityManager;
	}

	/**
	 * @param int $id
	 * @return ReviewState|null
	 */
	public function getStateReviewById(int $id): ?object
	{
		return $this->em->getRepository(ReviewState::class)->findOneBy(['id' => $id]);
	}

}
