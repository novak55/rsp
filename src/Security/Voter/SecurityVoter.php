<?php declare(strict_types = 1);

namespace App\Security\Voter;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use function in_array;

class SecurityVoter extends Voter
{

	public const JE_ADMIN_ORGANIZACE = 'jeAdminOrganizace';

	/**
	 * @var AuthorizationChecker
	 */
	private $authorizationChecker;
   
    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
	{
		$this->authorizationChecker = $authorizationChecker;
    }

	protected function supports($attribute, $subject): bool
	{
		return in_array($attribute, [
		    self::JE_ADMIN_ORGANIZACE,
        ]);
	}

	protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
	{

		switch ($attribute) {
			case self::JE_ADMIN_ORGANIZACE:
				return ($this->authorizationChecker->isGranted('ROLE_ADMIN'));
		}
		return false;
	}
}
