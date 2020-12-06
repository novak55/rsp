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
	 * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="reviews")
	 * @var User|null
	 */
	private $reviewer;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\EvaluationLevel")
	 * @ORM\JoinColumn(nullable=true)
	 * @var EvaluationLevel|null
	 */
	private $topicalityInterestAndUsefulness;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\EvaluationLevel")
	 * @ORM\JoinColumn(nullable=true)
	 * @var EvaluationLevel|null
	 */
	private $originalityAndProffesionalLevel;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\EvaluationLevel")
	 * @ORM\JoinColumn(nullable=true)
	 * @var EvaluationLevel|null
	 */
	private $languageAndStylisticLevel;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 * @var string|null
	 */
	private $comment;

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

	public function getTopicalityInterestAndUsefulness(): ?EvaluationLevel
	{
		return $this->topicalityInterestAndUsefulness;
	}

	public function setTopicalityInterestAndUsefulness(?EvaluationLevel $topicalityInterestAndUsefulness): void
	{
		$this->topicalityInterestAndUsefulness = $topicalityInterestAndUsefulness;
	}

	public function getOriginalityAndProffesionalLevel(): ?EvaluationLevel
	{
		return $this->originalityAndProffesionalLevel;
	}

	public function setOriginalityAndProffesionalLevel(?EvaluationLevel $originalityAndProffesionalLevel): void
	{
		$this->originalityAndProffesionalLevel = $originalityAndProffesionalLevel;
	}

	public function getLanguageAndStylisticLevel(): ?EvaluationLevel
	{
		return $this->languageAndStylisticLevel;
	}

	public function setLanguageAndStylisticLevel(?EvaluationLevel $languageAndStylisticLevel): void
	{
		$this->languageAndStylisticLevel = $languageAndStylisticLevel;
	}

	public function getComment(): ?string
	{
		return $this->comment;
	}

	public function setComment(?string $comment): void
	{
		$this->comment = $comment;
	}

}
