<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class CommentUnread
{

	/**
	 * @ORM\Id()
	 * @ORM\ManyToOne(targetEntity="App\Entity\User")
	 * @ORM\JoinColumn(nullable=false)
	 * @var User|null
	 */
	private $user;

	/**
	 * @ORM\Id()
	 * @ORM\ManyToOne(targetEntity="App\Entity\Comment", inversedBy="commentsUnread")
	 * @ORM\JoinColumn(nullable=false)
	 * @var Comment|null
	 */
	private $comment;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Review", inversedBy="commentsUnread")
	 * @ORM\JoinColumn(nullable=false)
	 * @var Review|null
	 */
	private $review;

	/**
	 * @ORM\Column(type="boolean", nullable=false)
	 * @var bool
	 */
	private $readed;

	public function __construct()
	{
		$this->readed = false;
	}

	public function getUser(): ?User
	{
		return $this->user;
	}

	public function setUser(?User $user): void
	{
		$this->user = $user;
	}

	public function getComment(): ?Comment
	{
		return $this->comment;
	}

	public function setComment(?Comment $comment): void
	{
		$this->comment = $comment;
	}

	public function getReview(): ?Review
	{
		return $this->review;
	}

	public function setReview(?Review $review): void
	{
		$this->review = $review;
	}

	public function isReaded(): bool
	{
		return $this->readed;
	}

	public function setReaded(bool $readed): void
	{
		$this->readed = $readed;
	}

}
