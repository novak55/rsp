<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Magazine
{

	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer", name="id")
	 * @var int|null
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\MagazineThema")
	 * @var MagazineThema|null
	 */
	private $magazineThema;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function setId(?int $id): void
	{
		$this->id = $id;
	}

	public function getMagazineThema(): ?MagazineThema
	{
		return $this->magazineThema;
	}

	public function setMagazineThema(?MagazineThema $magazineThema): void
	{
		$this->magazineThema = $magazineThema;
	}

}
