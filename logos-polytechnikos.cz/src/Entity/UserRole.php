<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class UserRole
{

	/**
	 * @ORM\Id()
	 * @ORM\Column(type="integer", unique=true)
	 * @var int|null
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=20, unique=true)
	 * @var string|null
	 */
	private $role;

	/**
	 * @ORM\Column(type="string", length=100)
	 * @var string|null
	 */
	private $description;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="role")
	 * @var User[]
	 */
	private $usersRole;

	public function __construct()
	{
		$this->usersRole = new ArrayCollection();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function setId(?int $id): void
	{
		$this->id = $id;
	}

	public function getRole(): ?string
	{
		return $this->role;
	}

	public function setRole(?string $role): void
	{
		$this->role = $role;
	}

	public function getDescription(): ?string
	{
		return $this->description;
	}

	public function setDescription(?string $description): void
	{
		$this->description = $description;
	}

	/**
	 * @return User[]
	 */
	public function getUsersRole(): array
	{
		return $this->usersRole;
	}

	/**
	 * @param User[] $usersRole
	 */
	public function setUsersRole(array $usersRole): void
	{
		$this->usersRole = $usersRole;
	}

}
