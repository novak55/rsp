<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Review;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use function count;

class FileRepository
{

	/** @var ManagerRegistry */
	private $em;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->em = $entityManager;
	}

	public function isUserReviewerOfArticle(User $reviewer, Article $article): bool
	{
		return count($this->em->createQueryBuilder()
			->select('r')
			->from(Review::class, 'r')
			->join('r.article', 'a')
			->join('r.reviewer', 'u')
			->where('r = :reviewer and a = :article')
			->setMaxResults(1)
			->setParameters([
				'reviewer' => $reviewer,
				'article' => $article,
			])) > 0;
	}

}
