<?php declare(strict_types = 1);

namespace App\Entity;

use App\Repository\ResetPasswordRequestRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestInterface;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestTrait;

/**
 * @ORM\Entity(repositoryClass=ResetPasswordRequestRepository::class)
 */
class ResetPasswordRequest implements ResetPasswordRequestInterface
{

	use ResetPasswordRequestTrait;

	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 * @var int
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\User")
	 * @ORM\JoinColumn(nullable=false)
	 * @var User
	 */
	private $user;

	public function __construct(object $user, DateTimeInterface $expiresAt, string $selector, string $hashedToken)
	{
		$this->user = $user;
		$this->initialize($expiresAt, $selector, $hashedToken);
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getUser(): object
	{
		return $this->user;
	}

}