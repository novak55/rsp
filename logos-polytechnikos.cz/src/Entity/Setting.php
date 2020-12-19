<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Setting
{

	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer", name="id")
	 * @var int|null
	 */
	private $id;

	/**
	 * @ORM\Column(type="string")
	 * @var string|null
	 */
	private $companyName;

	/**
	 * @ORM\Column(type="string")
	 * @var string|null
	 */
	private $domain;

	/**
	 * @ORM\Column(type="boolean")
	 * @var bool|null
	 */
	private $loginByEmail;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 * @var string|null
	 */
	private $instructions;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 * @var string|null
	 */
	private $reviewManagement;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 * @var string|null
	 */
	private $about;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 * @var string|null
	 */
	private $contact;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function setId(?int $id): void
	{
		$this->id = $id;
	}

	public function getCompanyName(): ?string
	{
		return $this->companyName;
	}

	public function setCompanyName(?string $companyName): void
	{
		$this->companyName = $companyName;
	}

	public function getDomain(): ?string
	{
		return $this->domain;
	}

	public function setDomain(?string $domain): void
	{
		$this->domain = $domain;
	}

	public function getLoginByEmail(): ?bool
	{
		return $this->loginByEmail;
	}

	public function setLoginByEmail(?bool $loginByEmail): void
	{
		$this->loginByEmail = $loginByEmail;
	}

	public function getInstructions(): ?string
	{
		return $this->instructions;
	}

	public function setInstructions(?string $instructions): void
	{
		$this->instructions = $instructions;
	}

	public function getReviewManagement(): ?string
	{
		return $this->reviewManagement;
	}

	public function setReviewManagement(string $reviewManagement): void
	{
		$this->reviewManagement = $reviewManagement;
	}

	public function getAbout(): ?string
	{
		return $this->about;
	}

	public function setAbout(?string $about): void
	{
		$this->about = $about;
	}

	public function getContact(): ?string
	{
		return $this->contact;
	}

	public function setContact(?string $contact): void
	{
		$this->contact = $contact;
	}

}
