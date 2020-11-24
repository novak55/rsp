<?php declare(strict_types = 1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class ArticleStateHistory
{

	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer", name="id")
	 * @var int|null
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\ArticleState")
	 * @ORM\JoinColumn(nullable=false)
	 * @var ArticleState
	 */
	private $articleState;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\User")
	 * @ORM\JoinColumn(nullable=false)
	 * @var User
	 */
	private $whoChanged;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Article", inversedBy="articleStatesHistory")
	 * @ORM\JoinColumn(nullable=false)
	 * @var Article
	 */
	private $article;

	/**
	 * @ORM\Column(type="datetime")
	 * @var DateTime
	 */
	private $date;

	public function __construct()
	{
		$this->date = new DateTime();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function setId(?int $id): void
	{
		$this->id = $id;
	}

	public function getArticleState(): ArticleState
	{
		return $this->articleState;
	}

	public function setArticleState(ArticleState $articleState): void
	{
		$this->articleState = $articleState;
	}

	public function getWhoChanged(): User
	{
		return $this->whoChanged;
	}

	public function setWhoChanged(User $whoChanged): void
	{
		$this->whoChanged = $whoChanged;
	}

	public function getDate(): DateTime
	{
		return $this->date;
	}

	public function setDate(DateTime $date): void
	{
		$this->date = $date;
	}

	public function getArticle(): Article
	{
		return $this->article;
	}

	public function setArticle(Article $article): void
	{
		$this->article = $article;
	}

}
