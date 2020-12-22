<?php declare(strict_types = 1);

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use function count;

/**
 * @ORM\Entity()
 */
class Article
{

	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer", name="id")
	 * @var int|null
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Magazine", inversedBy="articles")
	 * @var Magazine|null
	 */
	private $magazine;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	private $name;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="articles")
	 * @var User|null
	 */
	private $author;

	/**
	 * @ORM\Column(type="datetime")
	 * @var DateTime
	 */
	private $insertDate;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\ArticleState")
	 * @ORM\JoinColumn(nullable=false)
	 * @var ArticleState
	 */
	private $currentState;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\ArticleStateHistory", mappedBy="article", orphanRemoval=true, cascade={"persist"})
	 * @ORM\JoinColumn(nullable=false)
	 * @var ArrayCollection
	 */
	private $articleStatesHistory;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\FileAttachment")
	 * @ORM\JoinColumn(nullable=true)
	 * @var FileAttachment|null
	 */
	private $currentAttachment;

	/**
	 * @ORM\OneToMany (targetEntity="App\Entity\FileAttachment", mappedBy="article")
	 * @ORM\JoinColumn(nullable=true)
	 * @var Collection|FileAttachment[]|null
	 */
	private $attachments;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Review", mappedBy="article")
	 * @var Collection|Review[]|null
	 */
	private $reviews;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\ArticleCollaborator", mappedBy="article", cascade={"persist", "remove"}, orphanRemoval=true)
	 * @var ArticleCollaborator[]|Collection
	 */
	private $collaborators;

	public function __construct()
	{
		$this->reviews = new ArrayCollection();
		$this->collaborators = new ArrayCollection();
		$this->attachments = new ArrayCollection();
		$this->articleStatesHistory = new ArrayCollection();
		$this->insertDate = new DateTime();
	}

	public function addArticleStatesHistory(ArticleStateHistory $articleStateHistory): void
	{
		if ($this->articleStatesHistory->contains($articleStateHistory)) {
			return;
		}

		$this->articleStatesHistory[] = $articleStateHistory;
		$articleStateHistory->setArticle($this);
	}

	public function hasDifferentReviewersStatement(): bool
	{
		if (count($this->reviews) === 2) {
			$mem = [];
			foreach ($this->reviews as $review) {
				if ($review->getReviewerStatement() !== null && $review->getReviewerStatement()->getId() !== 2) {
					$mem[$review->getReviewerStatement()->getId()] = true;
				}
			}
			return count($mem) === 2;
		}
		return false;
	}
    
    public function hasFilledReviewersStatement(): bool
    {
        $countReviewers = count($this->reviews);
        if ($countReviewers > 1) {
            $mem = [];
            foreach ($this->reviews as $review) {
                if ($review->getReviewerStatement() !== null) {
                    $mem[] = $review->getReviewerStatement()->getId();
                }
            }
            return count($mem) === $countReviewers;
        }
        return false;
	}
	
	public function getId(): ?int
	{
		return $this->id;
	}

	public function setId(?int $id): void
	{
		$this->id = $id;
	}

	public function getMagazine(): ?Magazine
	{
		return $this->magazine;
	}

	public function setMagazine(?Magazine $magazine): void
	{
		$this->magazine = $magazine;
	}

	public function getAuthor(): ?User
	{
		return $this->author;
	}

	public function setAuthor(?User $author): void
	{
		$this->author = $author;
	}

	public function getInsertDate(): DateTime
	{
		return $this->insertDate;
	}

	public function setInsertDate(DateTime $insertDate): void
	{
		$this->insertDate = $insertDate;
	}

	public function getCurrentState(): ?ArticleState
	{
		return $this->currentState;
	}

	public function setCurrentState(ArticleState $currentState): void
	{
		$this->currentState = $currentState;
	}

	public function getArticleStatesHistory(): ArrayCollection
	{
		return $this->articleStatesHistory;
	}

	public function setArticleStatesHistory(ArrayCollection $articleStatesHistory): void
	{
		$this->articleStatesHistory = $articleStatesHistory;
	}

	public function getAttachments(): ?ArrayCollection
	{
		return $this->attachments;
	}

	public function setAttachments(?ArrayCollection $attachments): void
	{
		$this->attachments = $attachments;
	}

	/**
	 * @return Review[]|Collection|null
	 */
	public function getReviews()
	{
		return $this->reviews;
	}

	public function setReviews(ArrayCollection $reviews): void
	{
		$this->reviews = $reviews;
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	/**
	 * @return ArticleCollaborator[]|Collection
	 */
	public function getCollaborators()
	{
		return $this->collaborators;
	}

	/**
	 * @param ArticleCollaborator[]|Collection $collaborators
	 */
	public function setCollaborators($collaborators): void
	{
		$this->collaborators = $collaborators;
	}

	public function getCurrentAttachment(): ?FileAttachment
	{
		return $this->currentAttachment;
	}

	public function setCurrentAttachment(?FileAttachment $currentAttachment): void
	{
		$this->currentAttachment = $currentAttachment;
	}

}
