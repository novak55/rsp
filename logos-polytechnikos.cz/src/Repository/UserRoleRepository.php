<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\UserRole;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class UserRoleRepository
{
	/** @var ManagerRegistry */
	private $_em;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->_em = $entityManager;
	}

	/**
	 * @param string $name
	 * @return UserRole|null
	 */
	public function getRoleByName(string $role): ?object
	{
		return $this->_em->getRepository(UserRole::class)->findOneBy(['role' => $role]);
	}
}
