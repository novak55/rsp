<?php declare(strict_types = 1);

namespace App\Repository;

use App\Controller\UserController;
use App\Entity\Article;
use App\Entity\CommentUnread;
use App\Entity\Review;
use App\Entity\ReviewState;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use function count;
use function in_array;

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

	/**
	 * @param User $user
	 * @return Article[]|null
	 */
	public function myReviewedArticle(User $user): ?array
	{
		$qb = $this->em->createQueryBuilder()
			->select('a')
			->from(Article::class, 'a')
			->join('a.reviews', 'r')
		//          ->andWhere('a.currentState = ' . ArticleController::STAV_PREDANO_RECENZENTUM)
			->andWhere('r.reviewer = :user')
			->andWhere('r.reviewerStatement is not null')
			->setParameter('user', $user);
		return $qb->getQuery()->getResult();
	}

	public function countUnreadComments(User $user): int
	{
		$qb = $this->em->createQueryBuilder()
			->select('u')
			->from(CommentUnread::class, 'u')
			->where('u.user = :user and u.readed = false')
		    ->setParameter('user', $user);
		$result = $qb->getQuery()->getResult();
		return count((array) $result);
	}

	/**
	 * @param User $user
	 * @return Review[]|null
	 */
	public function getReviewHasComment(User $user): ?array
	{
		$qb = $this->em->createQueryBuilder()
			->select('r')
			->from(Review::class, 'r')
			->join('r.comments', 'c');
		if (in_array($user->getRole()->getId(), [2, 4], true)) {
			$qb->andWhere('r.reviewer = :user or c.user = :user')
				->setParameter('user', $user);
		}
		$qb->orderBy('c.insertDate', 'DESC');
		return $qb->getQuery()->getResult();
	}

	public function getLastCommentReview(User $user): ?Review
	{
		$qb = $this->em->createQueryBuilder()
			->select('r')
			->from(Review::class, 'r')
			->join('r.comments', 'c')
			->join('r.article', 'a')
			->where('a.author = :user or r.reviewer = :user')
			->setParameter('user', $user)
			->orderBy('c.insertDate', 'DESC')
			->setMaxResults(1);
		return $qb->getQuery()->getOneOrNullResult();
	}

}
