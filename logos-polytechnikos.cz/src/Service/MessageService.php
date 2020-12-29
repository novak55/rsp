<?php declare(strict_types = 1);

namespace App\Service;

use App\Repository\ArticleRepository;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessageService extends AbstractController
{

	/** @var ArticleRepository */
	private $articleRepository;

	/** @var ReviewRepository */
	private $reviewRepository;

	public function __construct(ArticleRepository $articleRepository, ReviewRepository $reviewRepository)
	{
		$this->articleRepository = $articleRepository;
		$this->reviewRepository = $reviewRepository;
	}

	/**
	 * @return int[]|string[]|array
	 */
	public function getMessages(): ?array
	{
		$typeAndMessage = ['secondary' => null];
		if ($this->getUser() !== null) {
			if ($this->isGranted('ROLE_REDAKTOR') || $this->isGranted('ROLE_SEFREDAKTOR')) {
				$countArticlesWithoutReviewers = $this->articleRepository->countArticlesWithoutReviewers();
				$countArticlesForDecision = $this->articleRepository->countArticlesForDecision();
				if ($countArticlesWithoutReviewers > 0) {
					$typeAndMessage['secondary'] .= '<a class="list-group-item list-group-item-info col" href="' . $this->generateUrl('app_article_articlewithoutreviewer') . '">Počet článků ke kterým je nutné přiřadit recenzenta: ' . $countArticlesWithoutReviewers . '!</a>';
				}
				if ($countArticlesForDecision > 0) {
					$typeAndMessage['secondary'] .= '<a class="list-group-item list-group-item-info col" href="' . $this->generateUrl('app_article_articlefordecision') . '">Počet článků pro rozhodnutí: ' . $countArticlesForDecision . '!</a>';
				}
			}
            $countUnreadComments = $this->reviewRepository->countUnreadComments($this->getUser());
            if ($countUnreadComments > 0) {
                $typeAndMessage['secondary'] .= '<a class="list-group-item list-group-item-info col" href="' . $this->generateUrl('app_comment_showreviewhascomment') . '">Máte nepřečtené komentáře v diskusi: ' . $countUnreadComments . '!</a>';
            }
			if ($typeAndMessage['secondary'] !== null) {
				$typeAndMessage['secondary'] = '<div class="row nowrap">' . $typeAndMessage['secondary'] . '</div>';
			}
			
		}
		return $typeAndMessage;
	}

}
