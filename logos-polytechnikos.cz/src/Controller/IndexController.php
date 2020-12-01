<?php declare(strict_types = 1);

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Security\SecurityService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{

	/** @var ArticleRepository */
	private $articleRepository;
	/** @var SecurityService */
	private $securityService;

	public function __construct(ArticleRepository $articleRepository, SecurityService $securityService)
	{
		$this->articleRepository = $articleRepository;
		$this->securityService = $securityService;
	}

	/**
	 * @Route("/", name="rsp")
	 * @return RedirectResponse|Response
	 */
	public function index()
	{
		if ($this->securityService->hasRole("ROLE_AUTOR")) {
			return $this->redirect('/my-articles');
		} else {
			return $this->render('rsp/index.html.twig', [
				'articles' => $this->articleRepository->getPublicArticle(),
			]);
		}
	}

}
