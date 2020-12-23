<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Magazine;
use App\Entity\MagazineState;
use App\Entity\MagazineThema;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class MagazineRepository
{

	/** @var ManagerRegistry */
	private $em;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->em = $entityManager;
	}

	/**
	 * @return Magazine[]|null
	 */
	public function getMagazines(bool $restrict): ?array
	{
		$where = $restrict ? ['currentState' => 3] : ['currentState' => [1, 2]];
		return $this->em->getRepository(Magazine::class)->findBy($where, ['deadline' => 'DESC']);
	}

	/**
	 * @return MagazineThema[]|null
	 */
	public function getMagazineThemes(): ?array
	{
		return $this->em->getRepository(MagazineThema::class)->findBy([], ['id' => 'ASC']);
	}

	public function hasThemeSomeMagazine(MagazineThema $magazineThema): bool
	{
		$qb = $this->em->createQueryBuilder()
			->select('count(m)')
			->from(Magazine::class, 'm')
			->join('m.magazineThema', 'mt')
			->where('mt = :mt')
			->setParameter('mt', $magazineThema);
		return $qb->getQuery()->getSingleScalarResult() > 0;
	}

	public function hasStateSomeMagazine(MagazineState $magazineState): bool
	{
		$qb = $this->em->createQueryBuilder()
			->select('count(m)')
			->from(Magazine::class, 'm')
			->join('m.currentState', 'cs')
			->where('cs = :ct')
			->setParameter('ct', $magazineState);
		return $qb->getQuery()->getSingleScalarResult() > 0;
	}

	/**
	 * @return MagazineState[]|null
	 */
	public function getMagazineStates(): ?array
	{
		return $this->em->getRepository(MagazineState::class)->findBy([], ['state' => 'ASC']);
	}

	public function getCurrentNumber(Magazine $magazine): int
	{
		$result = $this->em->createQueryBuilder()
			->select('count(m.number)')
			->from(Magazine::class, 'm')
			->where('m.deadline between :od and :do')
			->setParameter('od', $magazine->getDeadline()->format('Y') . '-01-01')
			->setParameter('do', $magazine->getDeadline()->modify('1 year')->format('Y') . '-01-01')
			->getQuery()->getSingleScalarResult();

		return $result + 1;
	}

	/**
	 * @param int $id
	 * @return MagazineState
	 */
	public function getMagazineStateById(int $id): object
	{
		return $this->em->getRepository(MagazineState::class)->findOneBy(['id' => $id]);
	}

	public function getLastDateMagazine(): ?string
	{
		$result = $this->em->createQueryBuilder()
			->select('m.deadline')
			->from(Magazine::class, 'm')
			->orderBy('m.deadline', 'DESC')
			->setMaxResults(1)
			->getQuery()
			->getOneOrNullResult();
		return $result['deadline']->format('Y-m-d');
	}

	public function hasMagazineSomeArticle(Magazine $magazine): bool
	{
		$qb = $this->em->createQueryBuilder()
			->select('count(a)')
			->from(Article::class, 'a')
			->join('a.magazine', 'm')
			->where('m = :m')
			->setParameter('m', $magazine);
		return $qb->getQuery()->getSingleScalarResult() > 0;
	}

}
