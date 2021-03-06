<?php declare(strict_types = 1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class FileAttachment
{

	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer", name="id")
	 * @var int|null
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 * @var string|null
	 */
	private $fileName;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	private $fileSystemName;

	/**
	 * @ORM\Column(type="string")
	 * @var string
	 */
	private $mimeType;

	/**
	 * @ORM\Column(type="datetime")
	 * @var DateTime
	 */
	private $inserted;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Article", inversedBy="attachments", fetch="EAGER")
	 * @ORM\JoinColumn(nullable=true)
	 * @var Article|null
	 */
	private $article;

	public function __construct()
	{
		$this->inserted = new DateTime();
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

	public function getFileName(): ?string
	{
		return $this->fileName;
	}

	public function setFileName(?string $fileName): void
	{
		$this->fileName = $fileName;
	}

	public function getFileSystemName(): string
	{
		return $this->fileSystemName;
	}

	public function setFileSystemName(string $fileSystemName): void
	{
		$this->fileSystemName = $fileSystemName;
	}

	public function getMimeType(): string
	{
		return $this->mimeType;
	}

	public function setMimeType(string $mimeType): void
	{
		$this->mimeType = $mimeType;
	}

	public function getInserted(): DateTime
	{
		return $this->inserted;
	}

	public function setInserted(DateTime $inserted): void
	{
		$this->inserted = $inserted;
	}

}
