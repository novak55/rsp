<?php declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Manager\RspManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController extends AbstractController
{

	/** @var FlashBagInterface */
	private $flashBag;

	/** @var FormFactoryInterface */
	private $formFactory;

	/** @var RspManager */
	private $manager;

	public function __construct(FlashBagInterface $flashBag, FormFactoryInterface $formFactory, RspManager $manager)
	{
		$this->flashBag = $flashBag;
		$this->formFactory = $formFactory;
		$this->manager = $manager;
	}

	/**
	 * @Route("/add-review/{review}")
	 * @param Request $request
	 * @param Review $review
	 * @return Response|RedirectResponse
	 */
	public function addReview(Request $request, Review $review): Response
	{
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		if ($review->getReviewer() !== $this->getUser()) {
			return $this->render('security/secerr.html.twig');
		}
		$form = $this->formFactory->create(ReviewType::class, $review);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$this->manager->save($review);
			$this->flashBag->add('success', 'Vaše recenze článku byla přidána.');
			return new RedirectResponse($this->generateUrl('rsp'));
		}
		return $this->render('review/add_review.html.twig', [
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/submit-review/{review}")
	 * @param Review $review
	 * @return RedirectResponse|Response
	 */
	public function submitReview(Review $review)
	{
		if ($review->getReviewer() !== $this->getUser()) {
			return $this->render('security/secerr.html.twig');
		}
		$review->setSubmitedReview(true);
		$this->manager->save($review);
		$this->flashBag->add('success', 'Vaše recenze článku ' . $review->getArticle()->getName() . ' byla odevzdána.');
		return new RedirectResponse($this->generateUrl('rsp'));
	}

	/**
	 * @Route("/show-review/{review}")
	 * @param Review $review
	 * @return Response
	 */
	public function showReview(Review $review): Response
	{
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		if (!($review->getReviewer() === $this->getUser()
			|| $review->getArticle()->getAuthor() === $this->getUser()
			|| $this->isGranted('ROLE_REDAKTOR')
			|| $this->isGranted('ROLE_SEFREDAKTOR'))
			|| $review->isSubmitedReview() !== true
		) {
			return $this->render('security/secerr.html.twig');
		}

		return $this->render('review/show_review.html.twig', [
			'review' => $review,
		]);
	}

}
