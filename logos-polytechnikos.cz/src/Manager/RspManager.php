<?php declare(strict_types = 1);

namespace App\Manager;

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

	public function odstranit(object $object): void
	{
		$this->em->remove($object);
		$this->em->flush();
	}

	public function ulozit(object $object): void
	{
		if ($object->getId() === null) {
			$this->em->persist($object);
		}
		$this->em->flush();
	}

	public function pridat(object $object): void
	{
		$this->em->persist($object);
		$this->em->flush();
	}

}
