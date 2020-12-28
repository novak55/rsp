<?php declare(strict_types = 1);

namespace App\Controller;

use App\Entity\FileAttachment;
use App\Entity\Setting;
use App\Entity\TepmlateHistory;
use App\Form\ChangeAboutType;
use App\Form\ChangeContactType;
use App\Form\ChangeInstructionType;
use App\Form\ChangeReviewManagementType;
use App\Form\ChangeTemplateType;
use App\Manager\RspManager;
use App\Repository\ArticleRepository;
use App\Repository\FileRepository;
use App\Repository\SettingsRepository;
use App\Service\FileUploader;
use DateTime;
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

	/**
	 * @Route("/about")
	 * @return Response
	 */
	public function about(): Response
	{
		return $this->render('rsp/show_about.html.twig');
	}

	/**
	 * @Route("/change-about")
	 * @param Request $request
	 * @return Response|RedirectResponse
	 */
	public function changeAbout(Request $request)
	{
		if (!$this->isGranted('ROLE_REDAKTOR')) {
			return $this->render('security/secerr.html.twig');
		}
		$form = $this->formFactory->create(ChangeAboutType::class, $this->settings);
		$form->handleRequest($request);
		if ($form->isSubmitted()) {
			if ($form->isValid()) {
				$this->manager->save($this->settings);
				$this->flashBag->add('success', 'Informace o časopise byly úspěšně uloženy.');
				return new RedirectResponse($this->generateUrl('app_index_changeabout'));
			}

			$this->flashBag->add('warning', 'Nelze uložit prázdný formulář s informacemi o časopise.');
			return new RedirectResponse($this->generateUrl('app_index_changeabout'));
		}
		return $this->render('rsp/change_about.html.twig', [
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/change-contact")
	 * @param Request $request
	 * @return Response|RedirectResponse
	 */
	public function changeContact(Request $request)
	{
		if (!$this->isGranted('ROLE_REDAKTOR')) {
			return $this->render('security/secerr.html.twig');
		}
		$form = $this->formFactory->create(ChangeContactType::class, $this->settings);
		$form->handleRequest($request);
		if ($form->isSubmitted()) {
			if ($form->isValid()) {
				$this->manager->save($this->settings);
				$this->flashBag->add('success', 'Kontaktní informace o byly úspěšně uloženy.');
				return new RedirectResponse($this->generateUrl('app_index_changecontact'));
			}

			$this->flashBag->add('warning', 'Nelze uložit prázdný formulář s kontakními informacemi.');
			return new RedirectResponse($this->generateUrl('app_index_changecontact'));
		}
		return $this->render('rsp/change_contact.html.twig', [
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/change-template")
	 * @param Request $request
	 * @param FileRepository $fileRepository
	 * @param FileUploader $fileUploader
	 * @return Response|RedirectResponse
	 */
	public function changeTemplate(Request $request, FileRepository $fileRepository, FileUploader $fileUploader)
	{
		if (!($this->isGranted('ROLE_REDAKTOR') || $this->isGranted('ROLE_SEFREDAKTOR') )) {
			return $this->render('security/secerr.html.twig');
		}
		$templateHistory = new TepmlateHistory();
		$form = $this->formFactory->create(ChangeTemplateType::class, $templateHistory);
		$form->handleRequest($request);
		if ($form->isSubmitted()) {
			if ($form->isValid()) {
				$templateHistory->setWhoChanged($this->getUser());
				$this->manager->save($templateHistory);
				$fileAttachment = new FileAttachment();
				$file = $form->get('articleTemplate')->getData();

				if ($file !== null) {
					$fileAttachment->setMimeType($file->getMimeType());
					$fileAttachment->setFileName($file->getClientOriginalName());
					$fileUploader->setSubfolder('templates/' . (new DateTime())->format('Y-m-d'));
					$fileAttachment->setFileSystemName($fileUploader->upload($file));
					$this->manager->add($fileAttachment);
					$templateHistory->setArticleTemplate($fileAttachment);
					$this->settings->setActiveTemplate($templateHistory);
					$this->manager->save($templateHistory);
					$this->flashBag->add('success', 'Nová šablona byla úspěšně přidána.');
					return new RedirectResponse($this->generateUrl('app_index_changetemplate'));
				}
			}

			$this->flashBag->add('warning', 'Nelze uložit prázdný formulář se šablonou, nebo šablona nemá správný formát: DOCX.');
			return new RedirectResponse($this->generateUrl('app_index_changetemplate'));
		}
		return $this->render('rsp/change_template.html.twig', [
			'form' => $form->createView(),
			'templates' => $fileRepository->getTemplates(),
		]);
	}

	/**
	 * @Route("/set-default-template/{template}")
	 * @param TepmlateHistory $template
	 * @return RedirectResponse|Response
	 */
	public function setDefaultTemplate(TepmlateHistory $template)
	{
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		if (!$this->isGranted('ROLE_REDAKTOR')) {
			return $this->render('security/secerr.html.twig');
		}
		$this->settings->setActiveTemplate($template);
		$this->manager->saveData();
		$this->flashBag->add('success', 'Aktuální šablona byla nastavena.');
		return new RedirectResponse($this->generateUrl('app_index_changetemplate'));
	}

	/**
	 * @Route("/articles-no-reviewer")
	 * @return RedirectResponse|Response
	 */
	public function articlesNoReviewer()
	{
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		if (!$this->isGranted('ROLE_REDAKTOR')) {
			return $this->render('security/secerr.html.twig');
		}

		return $this->render('article/articles_no_review.html.twig', [
			'articles' => $this->articleRepository->getArticlesWithNoReviews(),
		]);		
	}

		/**
	 * @Route("/finish-reviews")
	 * @return RedirectResponse|Response
	 */
	public function finishReviews()
	{
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		if (!$this->isGranted('ROLE_REDAKTOR')) {
			return $this->render('security/secerr.html.twig');
		}

		return $this->render('article/articles_finished_review.html.twig', [
			'articles' => $this->articleRepository->getArticlesWithFinishedReviews(),
		]);		
	}

}
