<?php declare(strict_types = 1);

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

	/** @var FlashBagInterface */
	private $flashBag;

	/** @var SessionInterface */
	private $session;

	/**
	 * SecurityController constructor.
	 *
	 * @param UserService $userService
	 */
	public function __construct(FlashBagInterface $flashBag, SessionInterface $session)
	{
		$this->flashBag = $flashBag;
		$this->session = $session;
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
	public function logout()
	{
		/* odhlášení ze systému */
	}

	/**
	 * @Route("/power-off")
	 * @return RedirectResponse
	 */
	public function powerOff(): RedirectResponse
	{
		if ($this->session->has('enterPage')) {
			$this->session->set('enterPage', false);
		}
		return new RedirectResponse('/');
	}
	
	/**
	 * @Route("/power-on")
	 * @return RedirectResponse
	 */
	public function powerOn(): RedirectResponse
	{
    	$this->session->set('enterPage', true);
		return new RedirectResponse('/');
	}

}
