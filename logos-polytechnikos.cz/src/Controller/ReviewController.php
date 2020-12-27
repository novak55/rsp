<?php declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Review;
use App\Form\ComplaintType;
use App\Form\ReviewerResultType;
use App\Form\ReviewType;
use App\Manager\RspManager;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController extends AbstractController
{

	public const ROZPRACOVANO = 1;
	public const ODESLANO_REDAKTOROVI = 2;
	public const ZPRISTUPNENO_AUTOROVI = 3;

	/** @var FlashBagInterface */
	private $flashBag;

	/** @var FormFactoryInterface */
	private $formFactory;

	/** @var RspManager */
	private $manager;

	private $reviewRepository;

	public function __construct(
		FlashBagInterface $flashBag,
		FormFactoryInterface $formFactory,
		RspManager $manager,
		ReviewRepository $reviewRepository
	)
	{
		$this->flashBag = $flashBag;
		$this->formFactory = $formFactory;
		$this->manager = $manager;
		$this->reviewRepository = $reviewRepository;
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
			return new RedirectResponse($this->generateUrl('show_article_detail', ['article' => $review->getArticle()->getId()]));
		}
		return $this->render('review/add_review.html.twig', [
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/submit-review/{review}")
	 * @param Request $request
	 * @param Review $review
	 * @return RedirectResponse|Response
	 */
	public function submitReview(Request $request, Review $review)
	{
		if ($review->getReviewer() !== $this->getUser()) {
			return $this->render('security/secerr.html.twig');
		}
		$form = $this->createForm(ReviewerResultType::class, $review);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$review->setReviewState($this->reviewRepository->getStateReviewById(self::ODESLANO_REDAKTOROVI));
			$this->manager->save($review);
			$this->flashBag->add('success', 'Vaše recenze článku ' . $review->getArticle()->getName() . ' byla odevzdána.');
			return new RedirectResponse($this->generateUrl('show_article_detail', ['article' => $review->getArticle()->getId()]));
		}
		return $this->render('review/reviewer_result.html.twig', [
			'form' => $form->createView(),
		]);
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
			|| ($review->getArticle()->getAuthor() === $this->getUser() && $review->getReviewState()->getId() === self::ZPRISTUPNENO_AUTOROVI)
			|| $this->isGranted('ROLE_REDAKTOR')
			|| $this->isGranted('ROLE_SEFREDAKTOR'))
			|| $review->getReviewState()->getId() === 1
		) {
			return $this->render('security/secerr.html.twig');
		}

		return $this->render('review/show_review.html.twig', [
			'review' => $review,
		]);
	}

	/**
	 * @Route("/review/{review}/complaint")
	 * @param Request $request
	 * @param Review $review
	 * @return Response
	 */
	public function submitComplaint(Request $request, Review $review): Response
	{
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		if (!($review->getArticle()->getAuthor() === $this->getUser() && $review->getReviewState()->getId() === 3)) {
			return $this->render('security/secerr.html.twig');
		}

		$form = $this->formFactory->create(ComplaintType::class);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			// TODO: Uložit námitku
			$this->flashBag->add('success', 'Vaše námitka byla zaslána redaktorovi.');
		}

		return $this->render('review/review_complaint.html.twig', [
			'review' => $review,
			'complaintBody' => 'Kaboom',
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/my-reviewed-article")
	 * @return Response
	 */
	public function myReviewedArticle(): Response
	{
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		if (!$this->isGranted('ROLE_RECENZENT')) {
			return $this->render('security/secerr.html.twig');
		}

		return $this->render('review/my_reviewed_article.html.twig', [
			'articles' => $this->reviewRepository->myReviewedArticle($this->getUser()),
			'user' => $this->getUser(),
		]);
	}

}
