<?php declare(strict_types = 1);

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class TepmlateHistory
{

	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer", name="id")
	 * @var int|null
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\User")
	 * @ORM\JoinColumn(nullable=false)
	 * @var User
	 */
	private $whoChanged;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\FileAttachment")
	 * @ORM\JoinColumn(nullable=true)
	 * @var FileAttachment|null
	 */
	private $articleTemplate;

	/**
	 * @ORM\Column(type="datetime_immutable")
	 * @var DateTimeImmutable
	 */
	private $date;

	public function __construct()
	{
		$this->date = new DateTimeImmutable();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function setId(?int $id): void
	{
		$this->id = $id;
	}

	public function getWhoChanged(): User
	{
		return $this->whoChanged;
	}

	public function setWhoChanged(User $whoChanged): void
	{
		$this->whoChanged = $whoChanged;
	}

	public function getArticleTemplate(): ?FileAttachment
	{
		return $this->articleTemplate;
	}

	public function setArticleTemplate(?FileAttachment $articleTemplate): void
	{
		$this->articleTemplate = $articleTemplate;
	}

	public function getDate(): DateTimeImmutable
	{
		return $this->date;
	}

	public function setDate(DateTimeImmutable $date): void
	{
		$this->date = $date;
	}

}
