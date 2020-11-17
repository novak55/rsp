<?php declare(strict_types = 1);

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

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
	 * @ORM\ManyToOne(targetEntity="App\Entity\Magazine")
	 * @var Magazine|null
	 */
	private $magazine;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	private $name;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\User")
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
	private $aktualState;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\ArticleStateHistory", mappedBy="article")
	 * @ORM\JoinColumn(nullable=false)
	 * @var ArrayCollection
	 */
	private $articleStatesHistory;

	/**
	 * @ORM\OneToMany (targetEntity="App\Entity\FileAttachment", mappedBy="article")
	 * @ORM\JoinColumn(nullable=true)
	 * @var ArrayCollection|null
	 */
	private $attachments;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Review", mappedBy="article")
	 * @var ArrayCollection
	 */
	private $reviews;

	public function __construct()
	{
		$this->reviews = new ArrayCollection();
		$this->attachments = new ArrayCollection();
		$this->articleStatesHistory = new ArrayCollection();
		$this->insertDate = new DateTime();
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

	public function getAktualState(): ?ArticleState
	{
		return $this->aktualState;
	}

	public function setAktualState(ArticleState $aktualState): void
	{
		$this->aktualState = $aktualState;
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

	public function getReviews(): ArrayCollection
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

}
