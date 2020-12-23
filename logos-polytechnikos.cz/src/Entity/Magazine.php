<?php declare(strict_types = 1);

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
	 * @ORM\ManyToOne(targetEntity="App\Entity\MagazineThema", inversedBy="magazines")
	 * @var MagazineThema|null
	 */
	private $magazineThema;

	/**
	 * @ORM\Column(type="date_immutable", nullable=true)
	 * @var DateTimeImmutable|null
	 */
	private $deadline;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\MagazineState", inversedBy="magazines")
	 * @ORM\JoinColumn(nullable=false)
	 * @var MagazineState
	 */
	private $currentState;

	/**
	 * @ORM\Column(type="integer", nullable=false)
	 * @var int|null
	 */
	private $number;

	/**
	 * @ORM\OneToMany (targetEntity="App\Entity\Article", mappedBy="magazine")
	 * @ORM\JoinColumn(nullable=false)
	 * @var Collection|Article[]|null
	 */
	private $articles;

	public function __construct()
	{
		$this->articles = new ArrayCollection();
	}

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

	public function getDeadline(): ?DateTimeImmutable
	{
		return $this->deadline;
	}

	public function setDeadline(?DateTimeImmutable $deadline): void
	{
		$this->deadline = $deadline;
	}

	public function getCurrentState(): ?MagazineState
	{
		return $this->currentState;
	}

	public function setCurrentState(MagazineState $currentState): void
	{
		$this->currentState = $currentState;
	}

	public function getNumber(): ?int
	{
		return $this->number;
	}

	public function setNumber(?int $number): void
	{
		$this->number = $number;
	}

	/**
	 * @return Article[]|Collection|null
	 */
	public function getArticles()
	{
		return $this->articles;
	}

	/**
	 * @param Article[]|Collection|null $articles
	 */
	public function setArticles($articles): void
	{
		$this->articles = $articles;
	}

}
