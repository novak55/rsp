<?php declare(strict_types = 1);

namespace App\Repository;

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

}
