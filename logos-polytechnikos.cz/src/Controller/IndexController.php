<?php declare(strict_types = 1);

namespace App\Controller;

use App\Repository\ArticleRepository;
//SecurityService toto není potřeba - řeší se přes $this->isGranted('ROLE_xxx'), ale musí být extendovaný AbstractController
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{

	/** @var ArticleRepository */
	private $articleRepository;

	public function __construct(ArticleRepository $articleRepository/*, SecurityService $securityService*/)
	{
		$this->articleRepository = $articleRepository;
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

}
