<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use function in_array;

class CommentRepository
{

	/** @var ManagerRegistry */
	private $em;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->em = $entityManager;
	}

	/**
	 * @param User $user
	 * @return Comment[]|null
	 */
	public function getReviewWithUnreadedComment(User $user): ?array
	{
		$qb = $this->em->createQueryBuilder()
			->select('c')
			->from(Comment::class, 'c')
			->join('c.review', 'r')
			->leftJoin('c.commentsUnread', 'u', Join::WITH, 'u.user = :user')
			->where('u is null')
			->setParameter('user', $user);
		if (in_array($user->getRole()->getId(), [2, 4], true)) {
			$qb->join('r.article', 'a')->andWhere('r.reviewer = :user or a.author = :user');
		}
		return $qb->getQuery()->getResult();
	}

}
