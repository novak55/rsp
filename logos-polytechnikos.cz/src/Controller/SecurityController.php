<?php declare(strict_types = 1);

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
	/** @var FlashBagInterface */
	private $flashBag;
	/** @var UserService */
	private $userService;


	/**
	 * SecurityController constructor.
	 * @param UserService $userService
	 */
	public function __construct(FlashBagInterface $flashBag, UserService $userService)
	{
    	$this->flashBag = $flashBag;
    	$this->userService = $userService;
	}

	/**
	 * @Route("/login", name="login")
	 */
	public function login(Request $request, AuthenticationUtils $utils): Response
	{
		$error = $utils->getLastAuthenticationError();
		$lastUsername = $utils->getLastUsername();

		return $this->render('security/login.html.twig', [
			'error' => $error,
			'lastUsername' => $lastUsername,
		]);
	}

	/**
	 * @Route("/logout", name="logout")
	 */
	public function logout(): void
	{
		/* odhlášení ze systému */
	}

}
