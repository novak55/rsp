<?php declare(strict_types = 1);

namespace App\Manager;

use App\Entity\Article;
use App\Entity\ArticleState;
use App\Entity\ArticleStateHistory;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RspManager
{

	/** @var EntityManagerInterface */
	private $em;

	/** @var UserPasswordEncoderInterface */
	private $passwordEncoder;

	/**
	 * UserManager constructor.
	 */
	public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder)
	{
		$this->em = $em;
		$this->passwordEncoder = $passwordEncoder;
	}

	public function remove(object $object): void
	{
		$this->em->remove($object);
		$this->em->flush();
	}

	public function save(object $object): void
	{
		if ($object->getId() === null) {
			$this->em->persist($object);
		}
		$this->em->flush();
	}

	public function saveUser(User $user, ?string $password = null): void
	{
		$user->setPassword($user->getPassword() !== $password
			? $this->passwordEncoder->encodePassword($user, $user->getPassword())
			: $password);
		$this->save($user);
	}

	public function add(object $object): void
	{
		$this->em->persist($object);
		$this->em->flush();
	}

	public function changeArticleState(Article $article, ArticleState $articleState, User $user): void
	{
		$article->setCurrentState($articleState);
		$articleStateHistory = new ArticleStateHistory();
		$articleStateHistory->setWhoChanged($user);
		$articleStateHistory->setArticle($article);
		$articleStateHistory->setArticleState($articleState);
		$article->addArticleStatesHistory($articleStateHistory);
		$this->save($article);
	}

}
