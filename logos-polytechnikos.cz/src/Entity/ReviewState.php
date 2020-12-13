<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class ReviewState
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
	 * @ORM\OneToMany(targetEntity="App\Entity\Review", mappedBy="reviewState")
	 * @ORM\JoinColumn(nullable=false)
	 * @var Collection|Review[]
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

	public function getState(): string
	{
		return $this->state;
	}

	public function setState(string $state): void
	{
		$this->state = $state;
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
