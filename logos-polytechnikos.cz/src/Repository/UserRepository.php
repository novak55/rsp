<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\User;
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

}
