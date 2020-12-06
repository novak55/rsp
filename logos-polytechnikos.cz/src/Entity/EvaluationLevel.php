<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class EvaluationLevel
{

	/**
	 * @ORM\Id()
	 * @ORM\Column(type="integer", name="id")
	 * @var int
	 */
	private $id;

	public function getId(): int
	{
		return $this->id;
	}

	public function setId(int $id): void
	{
		$this->id = $id;
	}

}
