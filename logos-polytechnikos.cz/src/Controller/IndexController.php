<?php declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Setting;
use App\Form\ChangeInstructionType;
use App\Form\ChangeReviewManagementType;
use App\Manager\RspManager;
use App\Repository\ArticleRepository;
//SecurityService toto není potřeba - řeší se přes $this->isGranted('ROLE_xxx'), ale musí být extendovaný AbstractController
use App\Repository\SettingsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{

	/** @var ArticleRepository */
	private $articleRepository;

	/** @var FlashBagInterface */
	private $flashBag;

	/** @var FormFactoryInterface */
	private $formFactory;

	/** @var Setting */
	private $settings;

	/** @var RspManager */
	private $manager;

	public function __construct(
		ArticleRepository $articleRepository,
		FlashBagInterface $flashBag,
		FormFactoryInterface $formFactory,
		SettingsRepository $settingsRepository,
		RspManager $manager
	)
	{
		$this->articleRepository = $articleRepository;
		$this->flashBag = $flashBag;
		$this->formFactory = $formFactory;
		$this->settings = $settingsRepository->getSettings();
		$this->manager = $manager;
	}

	/**
	 * @Route("/", name="rsp")
	 * @return RedirectResponse|Response
	 */
	public function index()
	{
		return $this->isGranted('ROLE_AUTOR') ? $this->redirect('/my-articles') : $this->render('rsp/index.html.twig', [
			'articles' => $this->articleRepository->getArticleWidgetByUserAndRole($this->getUser()),
			'user' => $this->getUser(),
		]);
	}

	/**
	 * @Route("/show-instructions")
	 * @return Response
	 */
	public function showInstructions(): Response
	{
		return $this->render('rsp/show_instructions.html.twig');
	}

	/**
	 * @Route("/change-instruction")
	 * @param Request $request
	 * @return Response|RedirectResponse
	 */
	public function changeInstruction(Request $request)
	{
		if (!$this->isGranted('ROLE_REDAKTOR')) {
			return $this->render('security/secerr.html.twig');
		}
		$form = $this->formFactory->create(ChangeInstructionType::class, $this->settings);
		$form->handleRequest($request);
		if ($form->isSubmitted()) {
			if ($form->isValid()) {
				$this->manager->save($this->settings);
				$this->flashBag->add('success', 'Pokyny pro přispěvatele byly úspěšně upraveny.');
				return new RedirectResponse($this->generateUrl('app_index_changeinstruction'));
			}

			$this->flashBag->add('warning', 'Nelze uložit prázdný formulář s pokyny pro přispěvatele.');
			return new RedirectResponse($this->generateUrl('app_index_changeinstruction'));
		}
		return $this->render('rsp/change_instructions.html.twig', [
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/review-management")
	 * @return Response
	 */
	public function reviewManagement(): Response
	{
		return $this->render('rsp/show_review_management.html.twig');
	}

	/**
	 * @Route("/change-review-management")
	 * @param Request $request
	 * @return Response|RedirectResponse
	 */
	public function changeReviewManagement(Request $request)
	{
		if (!$this->isGranted('ROLE_REDAKTOR')) {
			return $this->render('security/secerr.html.twig');
		}
		$form = $this->formFactory->create(ChangeReviewManagementType::class, $this->settings);
		$form->handleRequest($request);
		if ($form->isSubmitted()) {
			if ($form->isValid()) {
				$this->manager->save($this->settings);
				$this->flashBag->add('success', 'Popis recenzního řízení byl úspěšně upraven.');
				return new RedirectResponse($this->generateUrl('app_index_changereviewmanagement'));
			}

			$this->flashBag->add('warning', 'Nelze uložit prázdný formulář s popisem recenzního řízení.');
			return new RedirectResponse($this->generateUrl('app_index_changereviewmanagement'));
		}
		return $this->render('rsp/change_review_management.html.twig', [
			'form' => $form->createView(),
		]);
	}

}
