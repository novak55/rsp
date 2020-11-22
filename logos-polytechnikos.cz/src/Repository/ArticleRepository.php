<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Article;
use App\Entity\ArticleState;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use function ksort;

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

	/**
	 * @param Article $article
	 * @return object[]
	 */
	public function getCollaborators(Article $article): array
	{
		$existing = [];
		foreach ($article->getCollaborators() as $collaborator) {
			$existing['c-' . $collaborator->getId()] = $collaborator;
		}
		ksort($existing);
		return $existing;
	}

	/**
	 * @param int $id
	 * @return ArticleState|null
	 */
	public function getStateArticleById(int $id): ?object
	{
		return $this->em->getRepository(ArticleState::class)->findOneBy(['id' => $id]);
	}

}
