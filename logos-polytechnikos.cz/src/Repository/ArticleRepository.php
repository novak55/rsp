<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class ArticleRepository
{

	/** @var ManagerRegistry */
	private $em;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->em = $entityManager;
	}

	/**
	 * @param User $author
	 * @return Article[]|null
	 */
	public function getUserArticles(User $author): ?array
	{
		return $this->em->getRepository(Article::class)->findBy(['author' => $author]);
	}

}
