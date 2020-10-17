<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Setting;
use Doctrine\ORM\EntityManagerInterface;

class SettingsRepository
{

	private const ID_SETTING = 1;

	/** @var Setting */
	private $settings;

	public function __construct(EntityManagerInterface $em)
	{
		$this->settings = $em->getRepository(Setting::class)->findOneBy(['id' => self::ID_SETTING]);
	}

	public function getSettings(): ?Setting
	{
		return $this->settings;
	}

}
