<?php declare(strict_types = 1);

namespace App\Manager;

use App\Entity\FileAttachment;
use Doctrine\ORM\EntityManagerInterface;

class RspManager
{

	/** @var EntityManagerInterface */
	private $em;

	/**
	 * UserManager constructor.
	 */
	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
	}

	public function remove(object $object): void
	{
		$this->em->remove($object);
		$this->em->flush();
	}

	public function save(object $object): void
	{
		if ($object->getId() === null) {
			$this->em->persist($object);
		}
		$this->em->flush();
	}

	public function add(object $object): void
	{
		$this->em->persist($object);
		$this->em->flush();
	}
	
}
