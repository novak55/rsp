<?php


namespace App\Security;

use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Security;

class SecurityService
{
	private $security;
	private $logger;

	public function __construct(Security $security, LoggerInterface $logger)
	{
		$this->security = $security;
		$this->logger = $logger;
	}

	/**
	 * @param string $role
	 * @return Boolean|null
	 */
	public function hasRole(string $role)
	{
		$user = $this->security->getUser();
		if($user == null) {
			return false;
		}
		
		if (in_array($role, $user->getRoles(), TRUE)) {
			return true;
		} else {
			return false;
		}
	}
}