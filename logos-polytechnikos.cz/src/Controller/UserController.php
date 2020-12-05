<?php declare(strict_types = 1);

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegisterType;
use App\Security\SecurityService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class UserController extends AbstractController
{
	/** @var FlashBagInterface */
	private $flashBag;
	/** @var UserService */
	private $userService;
	/** @var SecurityService */
	private $securityService;

	/**
	 * UserController constructor.
	 * @param UserService $userService
	 */
	public function __construct(FlashBagInterface $flashBag, UserService $userService, SecurityService $securityService)
	{
    	$this->flashBag = $flashBag;
    	$this->userService = $userService;
    	$this->securityService = $securityService;
	}

	/**
	 * @Route("/register/author")
	 * @param Request $request
	 * @return RedirectResponse|Response
	 */
	public function registerAuthor(Request $request, FormFactoryInterface $formFactory): Response
	{
	    $user = new User();
	    $form = $formFactory->create(UserRegisterType::class, $user);
	    $form->handleRequest($request);
	    
	    if ($form->isSubmitted() && $form->isValid()){
	    	try {
	    		$this->userService->createAuthor($user);
				$this->flashBag->add('success', 'Byl jste úspěšně zaregistrován jako autor. Můžete se přihlásit.');
				return new RedirectResponse($this->generateUrl('login'));
			} catch (\Exception $e) {
				$this->flashBag->add('warning', 'Nepodařilo se vytvořit účet kontaktujte administrátora webu.');
			}
        }
		return $this->render('user/register.html.twig', [
		    'form' => $form->createView(),
        ]);
	}

	/**
	 * @Route("/register/redaktor")
	 * @param Request $request
	 * @return RedirectResponse|Response
	 * @IsGranted("ROLE_REDAKTOR")
	 */
	public function registerEditor(Request $request, FormFactoryInterface $formFactory): Response
	{
		$user = new User();
		$form = $formFactory->create(UserRegisterType::class, $user);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()){
			try {
				switch ($user->getRolePlainText()) {
					case 'ROLE_SEFREDAKTOR':
						$this->userService->createEditorInChief($user);
						break;
					case 'ROLE_REDAKTOR':
						$this->userService->createEditor($user);
						break;
					case 'ROLE_RECENZENT':
						$this->userService->createReviewer($user);
						break;
					default:
						throw new Exception("");
				}
				$this->flashBag->add('success', 'Uživatel byl úspěšně vytvořen.');
				return new RedirectResponse($this->generateUrl('rsp'));
			} catch (\Exception $e) {
				$this->flashBag->add('warning', 'Nepodařilo se vytvořit účet kontaktujte administrátora webu.');
			}
		}
		return $this->render('user/register.html.twig', [
			'form' => $form->createView(),
		]);
	}
}
