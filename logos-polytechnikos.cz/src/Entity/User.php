<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Symfony\Component\Security\Core\User\UserInterface;
use function serialize;
use function unserialize;

/**
 * @ORM\Entity()
 */
class User implements UserInterface, Serializable
{

	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 * @var int|null
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=20, unique=true, name="login")
	 * @var string|null
	 */
	private $username;

	/**
	 * @ORM\Column(type="string", length=100)
	 * @var string|null
	 */
	private $password;

	/**
	 * @ORM\Column(type="string", length=40, nullable=true)
	 * @var string
	 */
	private $titleBeforeName;

	/**
	 * @ORM\Column(type="string", length=40, nullable=true)
	 * @var string
	 */
	private $titleAfterName;

	/**
	 * @ORM\Column(type="string", length=100)
	 * @var string|null
	 */
	private $name;

	/**
	 * @ORM\Column(type="string", length=100)
	 * @var string
	 */
	private $surname;

	/**
	 * @ORM\Column(type="string", length=100, unique=true)
	 * @var string|null
	 */
	private $email;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\UserRole", inversedBy="usersRole")
	 * @var UserRole|null
	 */
	private $role;

	/**
	 * @var string|null
	 */
	private $rolePlainText;

	public function getFullNameByName(): ?string
	{
		return $this->name . ' ' . $this->surname;
	}

	public function getFullNameBySurname(): ?string
	{
		return $this->surname . ' ' . $this->name;
	}

	/**
	 * @return string[]
	 */
	public function getRoles(): array
	{
		return [
			$this->role->getRole(),
		];
	}

	public function getSalt(): void
	{
		/** nutná implementace z interface */
	}

	public function eraseCredentials(): void
	{
		/** nutná implementace z interface */
	}

	public function serialize()
	{
		return serialize([
			$this->id,
			$this->username,
			$this->email,
			$this->password,
			$this->titleBeforeName,
			$this->titleAfterName,
			$this->name,
			$this->surname,
		]);
	}

	public function unserialize($serialized): void
	{
		[
			$this->id,
			$this->username,
			$this->email,
			$this->password,
			$this->titleBeforeName,
			$this->titleAfterName,
			$this->name,
			$this->surname,
		] = unserialize($serialized, ['allowed_classes' => false]);
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function setId(?int $id): void
	{
		$this->id = $id;
	}

	public function getUsername(): ?string
	{
		return $this->username;
	}

	public function setUsername(?string $username): void
	{
		$this->username = $username;
	}

	public function getPassword(): ?string
	{
		return $this->password;
	}

	public function setPassword(?string $password): void
	{
		$this->password = $password;
	}

	public function getTitleBeforeName(): ?string
	{
		return $this->titleBeforeName;
	}

	public function setTitleBeforeName(?string $titleBeforeName): void
	{
		$this->titleBeforeName = $titleBeforeName;
	}

	public function getTitleAfterName(): ?string
	{
		return $this->titleAfterName;
	}

	public function setTitleAfterName(?string $titleAfterName): void
	{
		$this->titleAfterName = $titleAfterName;
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	public function setName(?string $name): void
	{
		$this->name = $name;
	}

	public function getSurname(): ?string
	{
		return $this->surname;
	}

	public function setSurname(string $surname): void
	{
		$this->surname = $surname;
	}

	public function getEmail(): ?string
	{
		return $this->email;
	}

	public function setEmail(?string $email): void
	{
		$this->email = $email;
	}

	public function getRole(): ?UserRole
	{
		return $this->role;
	}

	public function setRole(?UserRole $role): void
	{
		$this->role = $role;
	}

	/**
	 * @return string|null
	 */
	public function getRolePlainText(): ?string
	{
		return $this->rolePlainText;
	}

	/**
	 * @param string|null $rolePlainText
	 */
	public function setRolePlainText(?string $rolePlainText): void
	{
		$this->rolePlainText = $rolePlainText;
	}


}
