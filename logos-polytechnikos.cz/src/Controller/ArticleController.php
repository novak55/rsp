<?php declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticleCollaborator;
use App\Entity\ArticleStateHistory;
use App\Form\AddArticleType;
use App\Manager\RspManager;
use App\Repository\ArticleRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{

	public const STAV_VYTVORENO = 1;

	/** @var ArticleRepository */
	private $articleRepository;

	/** @var FlashBagInterface */
	private $flashBag;

	/** @var FormFactoryInterface */
	private $formFactory;

	/** @var RspManager */
	private $manager;

	public function __construct(
		ArticleRepository $articleRepository,
		FlashBagInterface $flashBag,
		FormFactoryInterface $formFactory,
		RspManager $manager
	)
	{
		$this->articleRepository = $articleRepository;
		$this->flashBag = $flashBag;
		$this->formFactory = $formFactory;
		$this->manager = $manager;
	}

	/**
	 * @Route("/my-articles")
	 * @return Response
	 */
	public function myArticles(): Response
	{
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		$articles = $this->articleRepository->getUserArticles($this->getUser());
		return $this->render('atricle/my_articles.html.twig', [
			'articles' => $articles,
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
			if ($article->getId() === null) {
				$aktualState = $this->articleRepository->getStateArticleById(self::STAV_VYTVORENO);
				$article->setAuthor($this->getUser());
				$article->setInsertDate(new DateTime());
				$article->setAktualState($aktualState);
				$this->manager->ulozit($article);
				$articleStateHistory = new ArticleStateHistory();
				$articleStateHistory->setDate(new DateTime());
				$articleStateHistory->setWhoChanged($this->getUser());
				$articleStateHistory->setArticle($article);
				$articleStateHistory->setArticleState($aktualState);
				$this->manager->ulozit($articleStateHistory);
				$this->flashBag->add('success', 'Článek <strong>' . $article->getName() . '</strong> byl úspěšně založen.');
			}
			if ($form->get('addCollaborator')->isClicked()) {
				$collaborator = new ArticleCollaborator();
				$collaborator->setArticle($article);
				$collaborator->setDegreeBefore($request->request->get('add_article')['degreeBefore']);
				$collaborator->setName($request->request->get('add_article')['nameCollaborator']);
				$collaborator->setDegreeAfter($request->request->get('add_article')['degreeAfter']);
				$collaborator->setEmail($request->request->get('add_article')['email']);
				$this->manager->ulozit($collaborator);
				$this->flashBag->add('success', 'Spoluautor  <strong>' . $collaborator->getName() . '</strong> byl úspěšně přidán.');
				return new RedirectResponse($this->generateUrl('app_article_addarticle', ['article' => $article->getId()]));
			}
			return new RedirectResponse($this->generateUrl('app_article_myarticles'));
		}
		return $this->render('atricle/add_article.html.twig', [
			'article' => $article,
			'form' => $form->createView(),
		]);
	}
    
    public function modifyArticle(Request $request, ?Article $article = null)
    {
        return null;
	}
	
	/**
	 * @Route("/diasble-collaborator/{collaborator}")
	 * @param ArticleCollaborator $collaborator
	 * @return RedirectResponse
	 */
	public function disableColaborator(ArticleCollaborator $collaborator): RedirectResponse
	{
		if ($this->getUser() === $collaborator->getArticle()->getAuthor()) {
			$collaborator->setDisabled(true);
			$this->manager->ulozit($collaborator);
			$this->addFlash('danger', 'Spoluautor byl odstraněn.');
			return new RedirectResponse($this->generateUrl('app_article_addarticle', ['article' => $collaborator->getArticle()->getId()]));
		}

		$this->addFlash('danger', 'Tohoto spoluautora nemůžete odstranit! Nejedná se o vašeho spoluautora.');
		return new RedirectResponse($this->generateUrl('/'));
	}

}
