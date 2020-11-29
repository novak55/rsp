<?php


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

	public function create(User $user)
	{
		$user->setRole($this->userRoleRepository->getRoleByName("ROLE_AUTOR"));
		$user->setPassword($this->passwordEncoder->encodePassword($user ,$user->getPassword()));
		$this->rspManager->save($user);
	}
}
