<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use function trim;

/**
 * @ORM\Entity()
 */
class ArticleCollaborator
{

	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer", name="id")
	 * @var int|null
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Article", inversedBy="collaborators")
	 * @var Article
	 */
	private $article;

	/**
	 * @ORM\Column(type="string", length=35, nullable=true)
	 * @var string|null
	 */
	private $degreeBefore;

	/**
	 * @ORM\Column(type="string", nullable=false)
	 * @var string
	 */
	private $nameCollaborator;

	/**
	 * @ORM\Column(type="string", length=30, nullable=true)
	 * @var string|null
	 */
	private $degreeAfter;

	/**
	 * @ORM\Column(type="string", length=200, nullable=false)
	 * @var string
	 */
	private $email;

	/**
	 * @ORM\Column(type="boolean", nullable=false)
	 * @var bool
	 */
	private $disabled;

	public function __construct()
	{
		$this->disabled = false;
	}

	public function getFullName(): string
	{
		$fullName = '';
		if ($this->getDegreeBefore() !== null && trim($this->getDegreeBefore()) !== '') {
			$fullName .= $this->getDegreeBefore() . ' ';
		}
		$fullName .= $this->getNameCollaborator();
		if ($this->getDegreeAfter() !== null && trim($this->getDegreeAfter()) !== '') {
			$fullName .= ', ' . $this->getDegreeAfter();
		}
		return $fullName;
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function setId(?int $id): void
	{
		$this->id = $id;
	}

	public function getArticle(): Article
	{
		return $this->article;
	}

	public function setArticle(Article $article): void
	{
		$this->article = $article;
	}

	public function getDegreeBefore(): ?string
	{
		return $this->degreeBefore;
	}

	public function setDegreeBefore(?string $degreeBefore): void
	{
		$this->degreeBefore = $degreeBefore;
	}

	public function getNameCollaborator(): string
	{
		return $this->nameCollaborator;
	}

	public function setNameCollaborator(string $nameCollaborator): void
	{
		$this->nameCollaborator = $nameCollaborator;
	}

	public function getDegreeAfter(): ?string
	{
		return $this->degreeAfter;
	}

	public function setDegreeAfter(?string $degreeAfter): void
	{
		$this->degreeAfter = $degreeAfter;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function setEmail(string $email): void
	{
		$this->email = $email;
	}

	public function isDisabled(): bool
	{
		return $this->disabled;
	}

	public function setDisabled(bool $disabled): void
	{
		$this->disabled = $disabled;
	}

}
