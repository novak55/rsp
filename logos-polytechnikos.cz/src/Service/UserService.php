<?php
/** todo: Nepotřebný soubor, možné odstranit  */

namespace App\Service;

use App\Entity\User;
use App\Manager\RspManager;
use App\Repository\UserRepository;
use App\Repository\UserRoleRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{

	/** @var UserRepository */
	private $userRepository;
	/** @var UserRoleRepository */
	private $userRoleRepository;
	/** @var RspManager */
	private $rspManager;
	/** @var UserPasswordEncoderInterface */
	private $passwordEncoder;


	/**
	 * UserService constructor.
	 * @param UserRepository $userRepository
	 */
	public function __construct(
		UserRepository $userRepository,
		UserRoleRepository $userRoleRepository,
		RspManager $rspManager,
		UserPasswordEncoderInterface $passwordEncoder
	) {
		$this->userRepository = $userRepository;
		$this->userRoleRepository = $userRoleRepository;
		$this->rspManager = $rspManager;
		$this->passwordEncoder = $passwordEncoder;
	}

	public function createAuthor(User $user)
	{
		$this->createUser($user, "ROLE_AUTOR");
	}

	public function createEditorInChief(User $user)
	{
		$this->createUser($user, "ROLE_SEFREDAKTOR");
	}

	public function createEditor(User $user)
	{
		$this->createUser($user, "ROLE_REDAKTOR");
	}

	public function createReviewer(User $user)
	{
		$this->createUser($user, "ROLE_RECENZENT");
	}

	private function createUser(User $user, string  $role)
	{
		$user->setRole($this->userRoleRepository->getRole($user->getRolePlainText()));
		$user->setPassword($this->passwordEncoder->encodePassword($user ,$user->getPassword()));
		$this->rspManager->save($user);
	}
}
