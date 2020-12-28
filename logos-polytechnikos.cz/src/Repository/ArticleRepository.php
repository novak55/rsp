<?php declare(strict_types = 1);

namespace App\Repository;

use App\Controller\ArticleController;
use App\Entity\Article;
use App\Entity\ArticleState;
use App\Entity\Review;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use function implode;

class ArticleRepository
{

	/** @var ManagerRegistry */
	private $em;

	/** @var AuthorizationCheckerInterface */
	private $authorizationChecker;

	public function __construct(EntityManagerInterface $entityManager, AuthorizationCheckerInterface $authorizationChecker)
	{
		$this->em = $entityManager;
		$this->authorizationChecker = $authorizationChecker;
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
	 * @param int $id
	 * @return ArticleState|null
	 */
	public function getStateArticleById(int $id): ?object
	{
		return $this->em->getRepository(ArticleState::class)->findOneBy(['id' => $id]);
	}

	/**
	 * @return Article[]|null
	 */
	public function getPublicArticle(): array
	{
		return $this->em->getRepository(Article::class)->findBy(['currentState' => ArticleController::PUBLIC_STATES]);
	}

	/**
	 * @param User|null $user
	 * @return Article[]|null
	 */
	public function getArticleWidgetByUserAndRole(?User $user = null): array
	{
		$qb = $this->em->createQueryBuilder()
			->select('a')
			->from(Article::class, 'a');
		if ($user === null) {
			$qb->andWhere('a.currentState in (' . implode(',', ArticleController::PUBLIC_STATES) . ')');
		} else {
			if ($this->authorizationChecker->isGranted('ROLE_RECENZENT')) {
				$qb->join('a.reviews', 'r')
					->andWhere('a.currentState = ' . ArticleController::STAV_PREDANO_RECENZENTUM)
					->andWhere('r.reviewer = :user')
					->setParameter('user', $user);
			}
			if ($this->authorizationChecker->isGranted('ROLE_REDAKTOR')
				|| $this->authorizationChecker->isGranted('ROLE_SEFREDAKTOR')) {
				$qb->andWhere('a.currentState in (' . ArticleController::STAV_PREDANO_RECENZENTUM . ',' . ArticleController::STAV_PODANO . ')');
			}
		}
		return $qb->getQuery()->getResult();
	}

	/**
	 * @return Article[]|null
	 */
	public function getArticlesWithFinishedReviews() {
		$qb = $this->em->createQueryBuilder()
			->select('a')
			->from(Article::class, 'a')
			->andWhere('a.currentState = ' . ArticleController::STAV_PREDANO_RECENZENTUM);

		$sub1 = $this->em->createQueryBuilder()
			->select('r1')
			->from(Review::class, 'r1')
			->andWhere('r1.article = a')
			->andWhere('r1.reviewState = 1');
		
		$qb->andWhere($qb->expr()->not($qb->expr()->exists($sub1->getDQL())));

		return $qb->getQuery()->getResult();
	}

	/**
	 * @return Article[]|null
	 */
	public function getArticlesWithNoReviews() {
		$qb = $this->em->createQueryBuilder()
			->select('a')
			->from(Article::class, 'a')
			->andWhere('a.currentState = ' . ArticleController::STAV_PODANO);

		return $qb->getQuery()->getResult();
	}


}
