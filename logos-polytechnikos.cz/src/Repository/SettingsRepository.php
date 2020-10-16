<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Setting;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class SettingsRepository
{

	private const ID_SETTING = 1;

	/** @var EntityManager */
	private $em;

	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
	}

	public function getSettings(): ?Setting
	{
		return $this->em->getRepository(Setting::class)->findOneBy(['id' => self::ID_SETTING]);
	}

}
