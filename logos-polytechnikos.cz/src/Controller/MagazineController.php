<?php declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Magazine;
use App\Entity\MagazineState;
use App\Entity\MagazineThema;
use App\Form\MagazineStateType;
use App\Form\MagazineType;
use App\Form\TemeManagementType;
use App\Manager\RspManager;
use App\Repository\MagazineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/magazine")
 */
class MagazineController extends AbstractController
{

	/** @var FlashBagInterface */
	private $flashBag;

	/** @var FormFactoryInterface */
	private $formFactory;

	/** @var RspManager */
	private $manager;

	/** @var MagazineRepository */
	private $magazineRepository;

	public function __construct(
		FlashBagInterface $flashBag,
		FormFactoryInterface $formFactory,
		RspManager $manager,
		MagazineRepository $magazineRepository
	)
	{
		$this->flashBag = $flashBag;
		$this->formFactory = $formFactory;
		$this->manager = $manager;
		$this->magazineRepository = $magazineRepository;
	}

	/**
	 * @Route("/edition/{magazine}")
	 * @return Response
	 */
	public function edition(?Magazine $magazine = null): Response
	{
		return $this->render('magazine/edition.html.twig', [
			'edition' => $magazine,
			'magazines' => $this->magazineRepository->getMagazines(),
		]);
	}

	/**
	 * @Route("/edition-management/{magazine}")
	 */
	public function editionManagement(Request $request, ?Magazine $magazine = null)
	{
		if (!$this->isGranted('ROLE_REDAKTOR')) {
			return $this->render('security/secerr.html.twig');
		}

		if ($magazine === null) {
			$magazine = new Magazine();
		}
		$form = $this->formFactory->create(MagazineType::class, $magazine);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
//			$this->manager->save($magazine);
			$this->flashBag->add('success', 'Edice časopisu byla uložena.');
			return new RedirectResponse($this->generateUrl('app_magazine_edition'));
		}
		return $this->render('magazine/edition_management_ajax.html.twig', [
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/theme-management")
	 * @return RedirectResponse|Response
	 */
	public function themeManagement()
	{
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		if (!$this->isGranted('ROLE_REDAKTOR')) {
			return $this->render('security/secerr.html.twig');
		}

		return $this->render('magazine/theme_management.html.twig', [
			'themes' => $this->magazineRepository->getMagazineThemes(),
		]);
	}

	/**
	 * @Route("/set-theme/{magazineThema}")
	 * @param Request $request
	 * @param MagazineThema|null $magazineThema
	 * @return RedirectResponse|Response
	 */
	public function setTheme(Request $request, ?MagazineThema $magazineThema = null)
	{
		if (!$this->isGranted('ROLE_REDAKTOR')) {
			return $this->render('security/secerr.html.twig');
		}

		if ($magazineThema === null) {
			$magazineThema = new MagazineThema();
		}
		$form = $this->formFactory->create(TemeManagementType::class, $magazineThema);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$this->manager->save($magazineThema);
			$this->flashBag->add('success', 'Téma časopisu bylo uloženo.');
			return new RedirectResponse($this->generateUrl('app_magazine_thememanagement'));
		}
		return $this->render('magazine/set_theme_ajax.html.twig', [
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/erase-theme/{magazineThema}")
	 * @param MagazineThema|null $magazineThema
	 * @return RedirectResponse|Response
	 */
	public function eraseTheme(MagazineThema $magazineThema)
	{
		if (!$this->isGranted('ROLE_REDAKTOR')) {
			return $this->render('security/secerr.html.twig');
		}

		if ($this->magazineRepository->hasThemeSomeMagazine($magazineThema)) {
			$this->flashBag->add('warning', 'Nelze odstranit téma časopisu, které je již přiřazeno ke konkrétní edici.');
			return new RedirectResponse($this->generateUrl('app_magazine_thememanagement'));
		}

		$this->manager->remove($magazineThema);
		$this->flashBag->add('success', 'Téma časopisu bylo odstraněno.');
		return new RedirectResponse($this->generateUrl('app_magazine_thememanagement'));
	}

	/**
	 * @Route("/state-management")
	 * @return RedirectResponse|Response
	 */
	public function stateManagement()
	{
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		if (!$this->isGranted('ROLE_REDAKTOR')) {
			return $this->render('security/secerr.html.twig');
		}

		return $this->render('magazine/state_management.html.twig', [
			'states' => $this->magazineRepository->getMagazineStates(),
		]);
	}

	/**
	 * @Route("/set-state/{magazineState}")
	 * @param Request $request
	 * @param MagazineThema|null $magazineState
	 * @return RedirectResponse|Response
	 */
	public function setState(Request $request, ?MagazineState $magazineState = null)
	{
		if (!$this->isGranted('ROLE_REDAKTOR')) {
			return $this->render('security/secerr.html.twig');
		}

		if ($magazineState === null) {
			$magazineState = new MagazineState();
		}
		$form = $this->formFactory->create(MagazineStateType::class, $magazineState);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$this->manager->save($magazineState);
			$this->flashBag->add('success', 'Čísleník stavu edic byl uložen.');
			return new RedirectResponse($this->generateUrl('app_magazine_statemanagement'));
		}
		return $this->render('magazine/set_state_ajax.html.twig', [
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/erase-state/{magazineState}")
	 * @param MagazineState|null $magazineState
	 * @return RedirectResponse|Response
	 */
	public function eraseState(MagazineState $magazineState)
	{
		if (!$this->isGranted('ROLE_REDAKTOR')) {
			return $this->render('security/secerr.html.twig');
		}

		if ($this->magazineRepository->hasStateSomeMagazine($magazineState)) {
			$this->flashBag->add('warning', 'Nelze odstranit téma časopisu, které je již přiřazeno ke konkrétní edici.');
			return new RedirectResponse($this->generateUrl('app_magazine_statemanagement'));
		}

		$this->manager->remove($magazineState);
		$this->flashBag->add('success', 'Téma časopisu bylo z číselníku odstraněno.');
		return new RedirectResponse($this->generateUrl('app_magazine_statemanagement'));
	}

}
