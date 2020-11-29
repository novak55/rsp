<?php declare(strict_types = 1);

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

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
	 * @Route("/register")
	 */
	public function register(Request $request, FormFactoryInterface $formFactory): Response
	{
	    $user = new User();
	    $form = $formFactory->create(RegisterType::class, $user);
	    $form->handleRequest($request);
	    
	    if ($form->isSubmitted() && $form->isValid()){
	    	try {
	    		$this->userService->create($user);
				$this->flashBag->add('success', 'Byl jste úspěšně zaregistrován jako autor. Můžete se přihlásit.');
				return $this->render('security/login.html.twig', [
					'lastUsername' => "",
					'error' => "",
				]);
			} catch (UniqueConstraintViolationException $e) {
				$this->flashBag->add('warning', 'Nepodařilo se vytvořit účet kontaktujte administrátora webu.');
			}
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
