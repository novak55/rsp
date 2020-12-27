<?php declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Review;
use App\Form\CommentType;
use App\Manager\RspManager;
use App\Repository\ReviewRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use function array_key_exists;

/**
 * @Route("/comment")
 */
class CommentController extends AbstractController
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
	 * @Route("/add/{review}")
	 * @param Request $request
	 * @param Review $review
	 * @return Response
	 */
	public function add(Request $request, Review $review): Response
	{
		if ($review->getReviewer() !== $this->getUser() && $review->getArticle()->getAuthor() !== $this->getUser()) {
			return $this->render('security/secerr.html.twig');
		}

		$this->getUser()->setLastReadComment(new DateTimeImmutable());
		$this->manager->saveData();

		$url = $request->query->has('url') ? $request->query->get('url') : $this->generateUrl('rsp');
		$comment = new Comment();
		$form = $this->formFactory->create(CommentType::class, $comment);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$this->saveComment($comment, $review);
			return new RedirectResponse($this->generateUrl('app_comment_add', ['review' => $review->getId(), 'url' => $url]));
		}
		$this->manager->setReadComments($review, $this->getUser());
		return $this->render(
			'comment/add.html.twig',
			[
				'form' => $form->createView(),
				'review' => $review,
				'url' => $url,
			]
		);
	}

	/**
	 * @Route("/edit-comment/{comment}")
	 * @param Request $request
	 * @param Comment $comment
	 * @return RedirectResponse|Response
	 */
	public function editComment(Request $request, Comment $comment)
	{
		if ($this->getUser() !== $comment->getUser()) {
			return $this->render('security/secerr.html.twig');
		}

		$url = $request->query->has('url') ? $request->query->get('url') : $this->generateUrl('rsp');
		$form = $this->formFactory->create(CommentType::class, $comment);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$this->manager->saveData();
			return new RedirectResponse($url);
		}
		return $this->render(
			'comment/edit_ajax.html.twig',
			[
				'form' => $form->createView(),
			]
		);
	}

	/**
	 * @Route("/show-review-has-comment/{review}")
	 * @param Request $request
	 * @param ReviewRepository $repository
	 * @param Review|null $review
	 * @return Response|RedirectResponse
	 */
	public function showReviewHasComment(Request $request, ReviewRepository $repository, ?Review $review = null): Response
	{
		if ($this->getUser() === null) {
			return $this->render('security/secerr.html.twig');
		}

		$lastCommentReview = $repository->getLastCommentReview($this->getUser());
		if ($review === null && $lastCommentReview !== null) {
			return new RedirectResponse($this->generateUrl('app_comment_showreviewhascomment', ['review' => $lastCommentReview->getId()]));
		}
		$reviews = $repository->getReviewHasComment($this->getUser());
		if ($review === null) {
			$review = array_key_exists(0, $reviews) ? $reviews[0] : null;
		}
		if ($review !== null) {
			$this->manager->setReadComments($review, $this->getUser());
		}
		return $this->render('comment/show-review-has-comment.hrml.twig', [
			'reviews' => $reviews,
			'review' => $review,
		//          'form' => $form->createView(),
		]);
	}

	private function saveComment(Comment $comment, Review $review): void
	{
		$comment->setUser($this->getUser());
		$comment->setReview($review);
		$this->manager->add($comment);
		$this->flashBag->add('success', 'Komentář byl k recenzi článku přidán.');
	}

}
