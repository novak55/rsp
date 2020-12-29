<?php declare(strict_types = 1);

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use function count;

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
	private $originality;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\EvaluationLevel")
	 * @ORM\JoinColumn(nullable=true)
	 * @var EvaluationLevel|null
	 */
	private $proffesionalLevel;

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

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\ReviewState", inversedBy="reviews")
	 * @ORM\JoinColumn(nullable=false)
	 * @var ReviewState
	 */
	private $reviewState;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\ReviewerStatementState", inversedBy="reviews")
	 * @ORM\JoinColumn(nullable=true)
	 * @var ReviewerStatementState|null
	 */
	private $reviewerStatement;

	/**
	 * @ORM\Column(type="datetime_immutable", nullable=false)
	 * @var DateTimeImmutable
	 */
	private $insertDate;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="review")
	 * @ORM\JoinColumn(nullable=false)
	 * @var Collection|Comment[]|null
	 */
	private $comments;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\CommentUnread", mappedBy="review")
	 * @ORM\JoinColumn(nullable=false)
	 * @var Collection|CommentUnread[]|null
	 */
	private $commentsUnread;

	public function __construct()
	{
		$this->insertDate = new DateTimeImmutable();
		$this->comment = new ArrayCollection();
		$this->commentsUnread = new ArrayCollection();
	}
	
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

	public function getOriginality(): ?EvaluationLevel
	{
		return $this->originality;
	}

	public function setOriginality(?EvaluationLevel $originality): void
	{
		$this->originality = $originality;
	}

	public function getProffesionalLevel(): ?EvaluationLevel
	{
		return $this->proffesionalLevel;
	}

	public function setProffesionalLevel(?EvaluationLevel $proffesionalLevel): void
	{
		$this->proffesionalLevel = $proffesionalLevel;
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

	public function getReviewState(): ReviewState
	{
		return $this->reviewState;
	}

	public function setReviewState(ReviewState $reviewState): void
	{
		$this->reviewState = $reviewState;
	}

	public function getReviewerStatement(): ?ReviewerStatementState
	{
		return $this->reviewerStatement;
	}

	public function setReviewerStatement(?ReviewerStatementState $reviewerStatement): void
	{
		$this->reviewerStatement = $reviewerStatement;
	}

	public function getInsertDate(): DateTimeImmutable
	{
		return $this->insertDate;
	}

	public function setInsertDate(DateTimeImmutable $insertDate): void
	{
		$this->insertDate = $insertDate;
	}

	/**
	 * @return Comment[]|Collection|null
	 */
	public function getComments()
	{
		return $this->comments;
	}

	/**
	 * @param Comment[]|Collection|null $comments
	 */
	public function setComments($comments): void
	{
		$this->comments = $comments;
	}

	/**
	 * @return CommentUnread[]|Collection|null
	 */
	public function getCommentsUnread()
	{
		return $this->commentsUnread;
	}

	/**
	 * @param CommentUnread[]|Collection|null $commentsUnread
	 */
	public function setCommentsUnread($commentsUnread): void
	{
		$this->commentsUnread = $commentsUnread;
	}

}
