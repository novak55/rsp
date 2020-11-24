<?php declare(strict_types = 1);

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{

	/** @var ArticleRepository */
	private $articleRepository;

	public function __construct(ArticleRepository $articleRepository)
	{
		$this->articleRepository = $articleRepository;
	}

	/**
	 * @Route("/", name="rsp")
	 * @return RedirectResponse|Response
	 */
	public function index()
	{
		return $this->render('rsp/index.html.twig', [
			'articles' => $this->articleRepository->getPublicArticle(),
		]);
	}

}
