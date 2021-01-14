<?php declare(strict_types = 1);

namespace App\Service;

use App\Controller\ArticleController;
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
		$typeAndMessage['secondary'] = null;
		$typeAndMessage['warning'] = null;
		$typeAndMessage['danger'] = null;
		$typeAndMessage['success'] = null;
		$typeAndMessage['primary'] = null;
		if ($this->getUser() !== null) {
			if ($this->isGranted('ROLE_REDAKTOR') || $this->isGranted('ROLE_SEFREDAKTOR')) {
				$countArticlesWithoutReviewers = $this->articleRepository->countArticlesWithoutReviewers();
				$countArticlesForDecision = $this->articleRepository->countArticlesForDecision();
				if ($countArticlesWithoutReviewers > 0) {
					$typeAndMessage['secondary'] .= '<span class="col"><i class="fa fa-info-circle text-info"></i> <a data-toggle="tooltip" data-placement="bottom" href="' . $this->generateUrl('app_article_articlewithoutreviewer') . '">Počet článků ke kterým je nutné přiřadit recenzenta: ' . $countArticlesWithoutReviewers . '</a></span>';
				}
				if ($countArticlesForDecision > 0) {
					$typeAndMessage['secondary'] .= '<span class="col"><i class="fa fa-info-circle text-info"></i> <a data-toggle="tooltip" data-placement="bottom" href="' . $this->generateUrl('app_article_articlefordecision') . '">Počet článků pro rozhodnutí: ' . $countArticlesForDecision . '</a></span>';
				}
			}
			if ($this->isGranted('ROLE_AUTOR')) {
				$duringPreviousDays = 14;
				$countArticlesInReviewStateDuringPrviousDays = $this->articleRepository->getCountArticlesInAskedStateDuringPrviousDays($duringPreviousDays, $this->getUser(), $this->articleRepository->getStateArticleById(ArticleController::STAV_PREDANO_RECENZENTUM));
				$countArticlesForRevisionDuringPrviousDays = $this->articleRepository->getCountArticlesInAskedStateDuringPrviousDays($duringPreviousDays, $this->getUser(), $this->articleRepository->getStateArticleById(ArticleController::STAV_VRACENO));
				$countRefusedArticlesDuringPrviousDays = $this->articleRepository->getCountArticlesInAskedStateDuringPrviousDays($duringPreviousDays, $this->getUser(), $this->articleRepository->getStateArticleById(ArticleController::STAV_ZAMITNUTO));
				$countArticlesInReviewStateOverPrviousDays = $this->articleRepository->getCountArticlesInAskedStateOverPrviousDays($duringPreviousDays, $this->getUser(), $this->articleRepository->getStateArticleById(ArticleController::STAV_PREDANO_RECENZENTUM));
				$countAcceptedArticlesDuringPrviousDays = $this->articleRepository->getCountArticlesInAskedStateOverPrviousDays($duringPreviousDays, $this->getUser(), $this->articleRepository->getStateArticleById(ArticleController::STAV_PRIJATO));
				$countAcceptedArticlesDuringPrviousDays = $this->articleRepository->getCountArticlesInAskedStateOverPrviousDays($duringPreviousDays, $this->getUser(), $this->articleRepository->getStateArticleById(ArticleController::STAV_PRIJATO));
				if ($countAcceptedArticlesDuringPrviousDays > 0) {
					$typeAndMessage['secondary'] .= '<span class="col"><i class="fa fa-info-circle text-info"></i> <a data-toggle="tooltip" data-placement="bottom"
                    href="' . $this->generateUrl('app_article_myarticles', ['articleState' => 6]) . '"
                    title="Počet článků které byly přijaté během posledních ' . $duringPreviousDays . ' dní">Přijaté články: ' . $countAcceptedArticlesDuringPrviousDays . '</a></span>';
				}
				if ($countArticlesForRevisionDuringPrviousDays > 0) {
					$typeAndMessage['warning'] .= '<span class="col"><i class="fa fa-exclamation-triangle text-white"></i> <a data-toggle="tooltip" data-placement="bottom"
                    href="' . $this->generateUrl('app_article_myarticles', ['articleState' => 3]) . '"
                    title="Počet článků vrácených k přepracování za posledních ' . $duringPreviousDays . ' dní">Vrácené články: ' . $countArticlesForRevisionDuringPrviousDays . '</a></span>';
				}
				if ($countRefusedArticlesDuringPrviousDays > 0) {
					$typeAndMessage['danger'] .= '<span class="col"><i class="fa fa-exclamation-triangle text-white"></i> <a data-toggle="tooltip" data-placement="bottom"
                    href="' . $this->generateUrl('app_article_myarticles', ['articleState' => 7]) . '"
                    title="Počet článků zamítnutých za posledních ' . $duringPreviousDays . ' dní">Zamítnuté články: ' . $countRefusedArticlesDuringPrviousDays . '</a></span>';
				}
				if ($countArticlesInReviewStateDuringPrviousDays > 0) {
					$typeAndMessage['secondary'] .= '<span class="col"><i class="fa fa-info-circle text-info"></i> <a data-toggle="tooltip" data-placement="bottom"
                    href="' . $this->generateUrl('app_article_articlewithoutreviewer') . '"
                    title="Počet článků které jsou v recenzním řízení za posledních ' . $duringPreviousDays . ' dní">Články v recenzním řízení: ' . $countArticlesInReviewStateDuringPrviousDays . '</a></span>';
				}

				if ($countArticlesInReviewStateOverPrviousDays > 0) {
					$typeAndMessage['warning'] .= '<span class="col"><i class="fa fa-exclamation-triangle text-white"></i> <a data-toggle="tooltip" data-placement="bottom"
                    href="' . $this->generateUrl('app_article_articlewithoutreviewer') . '"
                    title="Počet článků které jsou v recenzním řízení déle než posledních ' . $duringPreviousDays . ' dní">Počet článků které jsou v recenzním řízení déle než posledních ' . $duringPreviousDays . ' dní: ' . $countArticlesInReviewStateOverPrviousDays . '</a></span>';
				}
			}
			$countUnreadComments = $this->reviewRepository->countUnreadComments($this->getUser());
			if ($countUnreadComments > 0) {
			    if($countUnreadComments == 1) {
                    $pluralComments = " nepřečtený komentář";
                } else if($countUnreadComments < 5) {
                    $pluralComments = " nepřečtených komentářů";
                }  else {
			        $pluralComments = " nepřečtených komentářů";
                }
                $typeAndMessage['secondary'] .= '<span class="col"><i class="fa fa-info-circle text-info"></i> <a data-toggle="tooltip" data-placement="bottom" href="' . $this->generateUrl('app_comment_showreviewhascomment') . '">Máte '.$countUnreadComments.' '.$pluralComments.' v diskusi. </a></span>';
            }

			foreach ($typeAndMessage as $type => $messages) {
				if ($typeAndMessage[$type] !== null) {
					$typeAndMessage[$type] = '<div class="row nowrap">' . $typeAndMessage[$type] . '</div>';
				}
			}

		}
		return $typeAndMessage;
	}

}
