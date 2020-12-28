<?php declare(strict_types = 1);

namespace App\EventListener;

use App\Entity\Comment;
use App\Entity\CommentUnread;
use App\Manager\RspManager;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use function count;

final class CommentHandler extends AbstractController
{

	/** @var CommentRepository */
	private $commentRepository;

	/** @var RspManager */
	private $manager;

	public function __construct(CommentRepository $commentRepository, RspManager $manager)
	{
		$this->commentRepository = $commentRepository;
		$this->manager = $manager;
	}

	private function addComment(Comment $comment): void
	{
		$commcommentUnreadnt = new CommentUnread();
		$commcommentUnreadnt->setUser($this->getUser());
		$commcommentUnreadnt->setReview($comment->getReview());
		$commcommentUnreadnt->setComment($comment);
		$this->manager->persist($commcommentUnreadnt);
	}

	public function __invoke(RequestEvent $event): void
	{
		if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
			return;
		}

		if ($this->getUser() === null) {
			return;
		}

		$reviews = $this->commentRepository->getReviewWithUnreadedComment($this->getUser());
		if (count((array) $reviews) <= 0) {
			return;
		}

		foreach ((array) $reviews as $review) {
			$this->addComment($review);
		}
		$this->manager->saveData();
	}

}
