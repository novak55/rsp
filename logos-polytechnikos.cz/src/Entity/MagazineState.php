<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class MagazineState
{

	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer", name="id")
	 * @var int|null
	 */
	private $id;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	private $state;

	/**
	 * @ORM\OneToMany(targetEntity = "App\Entity\Magazine", mappedBy="currentState")
	 * @ORM\JoinColumn(nullable=true)
	 * @var Collection|Magazine[]|null
	 */
	private $magazines;

	public function __construct()
	{
		$this->magazines = new ArrayCollection();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function setId(?int $id): void
	{
		$this->id = $id;
	}

	public function getState(): ?string
	{
		return $this->state;
	}

	public function setState(?string $state): void
	{
		$this->state = $state;
	}

	/**
	 * @return Magazine[]|Collection|null
	 */
	public function getMagazines()
	{
		return $this->magazines;
	}

	/**
	 * @param Magazine[]|Collection|null $magazines
	 */
	public function setMagazines($magazines): void
	{
		$this->magazines = $magazines;
	}

}
