<?php declare(strict_types = 1);

namespace App\Repository;

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
	public function getMagazines(): ?array
	{
		return $this->em->getRepository(Magazine::class)->findBy([], ['id' => 'DESC']);
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

}
