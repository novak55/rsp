<?php declare(strict_types = 1);

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /** @var UserService */
    private $userService;

    /**
     * SecurityController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
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
	 * @Route("/register")
	 */
	public function register(Request $request, FormFactoryInterface $formFactory): Response
	{
	    $user = new User();
	    $form = $formFactory->create(RegisterType::class, $user);
	    $form->handleRequest($request);
	    
	    if ($form->isSubmitted() && $form->isValid()){
            // TODO - [LR] - dořešit výjimky nějaká error page?
	    	$this->userService->create($user);
        }
		return $this->render('security/register.html.twig', [
		    'form' => $form->createView(),
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
