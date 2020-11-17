<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Review
{

	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer", name="id")
	 * @var int|null
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Article", inversedBy="reviews")
	 * @var Article|null
	 */
	private $article;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\User")
	 * @var User|null
	 */
	private $reviewer;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function setId(?int $id): void
	{
		$this->id = $id;
	}

	public function getArticle(): ?Article
	{
		return $this->article;
	}

	public function setArticle(?Article $article): void
	{
		$this->article = $article;
	}

	public function getReviewer(): ?User
	{
		return $this->reviewer;
	}

	public function setReviewer(?User $reviewer): void
	{
		$this->reviewer = $reviewer;
	}

}
