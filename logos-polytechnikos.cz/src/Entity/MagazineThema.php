<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class MagazineThema
{

	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer", name="id")
	 * @var int|null
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", nullable=false)
	 * @var string
	 */
	private $theme;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Magazine", mappedBy="magazineThema")
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

	public function getTheme(): ?string
	{
		return $this->theme;
	}

	public function setTheme(string $theme): void
	{
		$this->theme = $theme;
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
