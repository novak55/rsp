<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Review;
use App\Entity\TepmlateHistory;
use App\Entity\FileAttachment;
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
		$isReviewrer = $this->em->createQueryBuilder()
			->select('r')
			->from(Review::class, 'r')
			->join('r.article', 'a')
			->join('r.reviewer', 'u')
			->where('u = :reviewer and a = :article')
			->setMaxResults(1)
			->setParameters([
				'reviewer' => $reviewer,
				'article' => $article,
			])
			->getQuery()
			->getResult();
		return count($isReviewrer ?? []) > 0;
	}

	/**
	 * @return TepmlateHistory[]|null
	 */
	public function getTemplates(): ?array
	{
		return $this->em->getRepository(TepmlateHistory::class)->findBy([], ['date' => 'DESC']);
	}

	/**
     * @return FileAttachment[]|null
     */
	public function getAllFileAttachmentsByArticle(Article $article)
	{
	   return $this->em->getRepository(FileAttachment::class)->findBy(['article' => $article], ['id' => 'ASC']);
	}



}
