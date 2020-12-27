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
class Comment
{

	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer", name="id")
	 * @var int|null
	 */
	private $id;

	/**
	 * @ORM\Column(type="text", nullable=false)
	 * @var string|null
	 */
	private $text;

	/**
	 * @ORM\ManyToOne (targetEntity="App\Entity\Review", inversedBy="comments")
	 * @ORM\JoinColumn(nullable=false)
	 * @var Review|null
	 */
	private $review;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\User")
	 * @ORM\JoinColumn(nullable=false)
	 * @var User|null
	 */
	private $user;

	/**
	 * @ORM\Column(type="datetime_immutable", nullable=false)
	 * @var DateTimeImmutable
	 */
	private $insertDate;

	/**
	 * @ORM\OneToMany (targetEntity="App\Entity\CommentUnread", mappedBy="comment")
	 * @ORM\JoinColumn(nullable=false)
	 * @var Collection|Comment[]|null
	 */
	private $commentsUnread;

	public function __construct()
	{
		$this->insertDate = new DateTimeImmutable();
		$this->commentsUnread = new ArrayCollection();
	}

	public function isUnreadComment(User $user)
	{
		$unread = $this->getCommentsUnread()
			->matching(Criteria::create()
/*				->where(Criteria::expr()
					->eq('readed', 1))*/
				->andWhere(Criteria::expr()
					->eq('user', $user)));
		return !$unread[0]->isReaded();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function setId(?int $id): void
	{
		$this->id = $id;
	}

	public function getText(): ?string
	{
		return $this->text;
	}

	public function setText(?string $text): void
	{
		$this->text = $text;
	}

	public function getReview(): ?Review
	{
		return $this->review;
	}

	public function setReview(?Review $review): void
	{
		$this->review = $review;
	}

	public function getUser(): ?User
	{
		return $this->user;
	}

	public function setUser(?User $user): void
	{
		$this->user = $user;
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
	public function getCommentsUnread()
	{
		return $this->commentsUnread;
	}

	/**
	 * @param Comment[]|Collection|null $commentsUnread
	 */
	public function setCommentsUnread($commentsUnread): void
	{
		$this->commentsUnread = $commentsUnread;
	}

}
