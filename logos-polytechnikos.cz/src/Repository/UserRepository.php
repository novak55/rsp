<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserRole;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository
{

	/** @var ManagerRegistry */
	private $_em;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->_em = $entityManager;
	}

	public function getRoleById(int $idRole): UserRole
	{
		return $this->_em->getRepository(UserRole::class)->findOneBy(['id' => $idRole]);
	}

	/**
	 * @return User[]|null
	 */
	public function getUsers(): ?array
	{
		$qb = $this->_em->createQueryBuilder()
			->select('u,r,rw,a')
			->from(User::class, 'u')
			->join('u.role', 'r')
			->leftJoin('u.reviews', 'rw')
			->leftJoin('u.articles', 'a')
			->orderBy('u.surname', 'ASC');
		return $qb->getQuery()->getResult();
	}

}
