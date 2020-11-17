<?php declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Article;
use App\Form\AddArticleType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{

	/** @var ArticleRepository */
	private $articleRepository;

	/** @var FlashBagInterface */
	private $flashBag;

	/** @var FormFactoryInterface */
	private $formFactory;

	public function __construct(ArticleRepository $articleRepository, FlashBagInterface $flashBag, FormFactoryInterface $formFactory)
	{
		$this->articleRepository = $articleRepository;
		$this->flashBag = $flashBag;
		$this->formFactory = $formFactory;
	}

	/**
	 * @Route("/my-articles")
	 * @return Response
	 */
	public function myArticles(): Response
	{
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		return $this->render('atricle/my_articles.html.twig', [
			'articles' => $this->articleRepository->getUserArticles($this->getUser()),
		]);
	}

	/**
	 * @Route("/add-article/{article}")
	 * @param Request $request
	 * @param Article|null $article
	 * @return Response|RedirectResponse
	 */
	public function addArticle(Request $request, ?Article $article = null)
	{
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		if ($article === null) {
			$article = new Article();
		}
		$form = $this->formFactory->create(AddArticleType::class, $article);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$this->flashBag->add('success', 'Článek <strong>' . $article->getName() . '</strong> byl úspěšně podán.');
			return new RedirectResponse($this->generateUrl('app_article_myarticles'));
		}
		return $this->render('atricle/add_article.html.twig', [
			'form' => $form->createView(),
		]);
	}

}
