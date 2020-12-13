<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class ReviewerStatementState
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
	private $statement;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Review", mappedBy="reviewerStatement")
	 * @ORM\JoinColumn(nullable=true)
	 * @var Collection|Review[]|null
	 */
	private $reviews;

	public function __construct()
	{
		$this->reviews = new ArrayCollection();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function setId(?int $id): void
	{
		$this->id = $id;
	}

	public function getStatement(): string
	{
		return $this->statement;
	}

	public function setStatement(string $statement): void
	{
		$this->statement = $statement;
	}

	/**
	 * @return Review[]|Collection
	 */
	public function getReviews()
	{
		return $this->reviews;
	}

	/**
	 * @param Review[]|Collection $reviews
	 */
	public function setReviews($reviews): void
	{
		$this->reviews = $reviews;
	}

}
