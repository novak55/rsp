<?php declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticleCollaborator;
use App\Entity\ArticleState;
use App\Entity\ArticleStateHistory;
use App\Entity\FileAttachment;
use App\Form\ArticleCollaboratorType;
use App\Form\ArticleTypeArticle;
use App\Manager\RspManager;
use App\Repository\ArticleRepository;
use App\Service\FileUploader;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use function in_array;

class ArticleController extends AbstractController
{

	public const STAV_VYTVORENO = 1;
	public const STAV_PODANO = 2;
	public const STAV_VRACENO = 3;
	public const STAV_PREDANO_RECENZENTUM = 4;
	public const STAV_PRIJTO_VYHRADA = 5;
	public const STAV_PRIJATO = 6;
	public const STAV_ZAMITNUTO = 7;

	/* todo: STAV_PODANO je potřeba odstranit, přidáno z důvodu testování */
	public const PUBLIC_STATES = [self::STAV_PODANO, self::STAV_PRIJTO_VYHRADA, self::STAV_PRIJATO];

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
	 * @param FileUploader $fileUploader
	 * @param Article|null $article
	 * @return Response|RedirectResponse
	 */
	public function addArticle(Request $request, FileUploader $fileUploader, ?Article $article = null)
	{
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		if ($article !== null && $article->getAuthor() !== $this->getUser()) {
			return $this->render('security/secerr.html.twig');
		}
		if ($article === null) {
			$article = new Article();
		}
		$form = $this->formFactory->create(ArticleTypeArticle::class, $article, ['buttonAddCollaboratorDisabled' => false]);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			if ($article->getId() === null) {
				$aktualState = $this->articleRepository->getStateArticleById(self::STAV_VYTVORENO);
				$article->setAuthor($this->getUser());
				$article->setInsertDate(new DateTime());
				$article->setCurrentState($aktualState);
				$this->manager->save($article);
				$articleStateHistory = new ArticleStateHistory();
				$articleStateHistory->setDate(new DateTime());
				$articleStateHistory->setWhoChanged($this->getUser());
				$articleStateHistory->setArticle($article);
				$articleStateHistory->setArticleState($aktualState);
				$this->manager->save($articleStateHistory);
				$this->flashBag->add('success', 'Článek <strong>' . $article->getName() . '</strong> byl úspěšně založen.');
			}
			$fileAttachment = new FileAttachment();
			$file = $form->get('attachment')->getData();
			if ($file !== null) {
				$fileAttachment->setArticle($article);
				$fileAttachment->setMimeType($file->getMimeType());
				$fileAttachment->setFileName($file->getClientOriginalName());
				$fileUploader->setSubfolder((string) $article->getId());
				$fileAttachment->setFileSystemName($fileUploader->upload($file));
				$this->manager->add($fileAttachment);
				$article->setCurrentAttachment($fileAttachment);
				$this->manager->save($article);
			}
			if ($form->get('addCollaborator')->isClicked()) {
				$collaborator = new ArticleCollaborator();
				$collaborator->setArticle($article);
				$collaborator->setDegreeBefore($request->request->get('article_type_article')['degreeBefore']);
				$collaborator->setNameCollaborator($request->request->get('article_type_article')['nameCollaborator']);
				$collaborator->setDegreeAfter($request->request->get('article_type_article')['degreeAfter']);
				$collaborator->setEmail($request->request->get('article_type_article')['email']);
				$this->manager->save($collaborator);
				$this->flashBag->add('success', 'Spoluautor  <strong>' . $collaborator->getNameCollaborator() . '</strong> byl úspěšně přidán.');
				return new RedirectResponse($this->generateUrl('app_article_addarticle', ['article' => $article->getId()]));
			}
			return new RedirectResponse($this->generateUrl('app_article_myarticles'));
		}
		return $this->render('atricle/add_article.html.twig', [
			'article' => $article,
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/modify-article/{article}")
	 * @param Request $request
	 * @param FileUploader $fileUploader
	 * @param Article $article
	 * @return RedirectResponse|Response
	 */
	public function modifyArticle(Request $request, FileUploader $fileUploader, Article $article)
	{
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		if ($article->getAuthor() !== $this->getUser()) {
			return $this->render('security/secerr.html.twig');
		}

		return $this->addArticle($request, $fileUploader, $article);
	}

	/**
	 * @Route("/diasble-collaborator/{collaborator}")
	 * @param ArticleCollaborator $collaborator
	 * @return RedirectResponse
	 */
	public function disableColaborator(ArticleCollaborator $collaborator): RedirectResponse
	{
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		if ($collaborator->getArticle()->getAuthor() !== $this->getUser()) {
			return $this->render('security/secerr.html.twig');
		}
		if ($this->getUser() === $collaborator->getArticle()->getAuthor()) {
			$collaborator->setDisabled(true);
			$this->manager->save($collaborator);
			$this->addFlash('danger', 'Spoluautor byl odstraněn.');
			return new RedirectResponse($this->generateUrl('app_article_addarticle', ['article' => $collaborator->getArticle()->getId()]));
		}

		$this->addFlash('danger', 'Tohoto spoluautora nemůžete odstranit! Nejedná se o vašeho spoluautora.');
		return new RedirectResponse($this->generateUrl('/'));
	}

	/**
	 * @Route("/modify-collaborator/{collaborator}")
	 * @param Request $request
	 * @param ArticleCollaborator $collaborator
	 * @return RedirectResponse|Response
	 */
	public function modifyCollaborator(Request $request, ArticleCollaborator $collaborator)
	{
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		if ($collaborator->getArticle()->getAuthor() !== $this->getUser()) {
			return $this->render('security/secerr.html.twig');
		}
		$form = $this->createForm(ArticleCollaboratorType::class, $collaborator, ['buttonAddCollaboratorDisabled' => true]);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$this->manager->save($collaborator);
			$this->addFlash('success', 'Spoluautor byl upraven.');
			return $this->redirect($this->generateUrl('app_article_modifyarticle', ['article' => $collaborator->getArticle()->getId()]));
		}
		return $this->render('atricle/modify_collaborator_ajax.html.twig', [
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/submit-article/{article}")
	 * @param Article $article
	 * @return RedirectResponse|Response
	 */
	public function submitArticle(Article $article)
	{
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		if ($article->getAuthor() !== $this->getUser()) {
			return $this->render('security/secerr.html.twig');
		}
		if (in_array($article->getCurrentState()->getId(), [self::STAV_PODANO], true)) {
			$this->flashBag->add('danger', 'Článek byl již podán, nemůže být podán vícekrát. O dalším průběhu budete informováni.');
			return new RedirectResponse($this->generateUrl('app_article_myarticles'));
		}
		$articleState = $this->articleRepository->getStateArticleById(self::STAV_PODANO);
		$this->flashBag->add('success', 'Váš článek byl podán. O dalším průběhu budete informováni.');
		$this->changeArticleState($article, $articleState);
		return new RedirectResponse($this->generateUrl('app_article_myarticles'));
	}

	public function changeArticleState(Article $article, ArticleState $articleState): void
	{
		$article->setCurrentState($articleState);
		$articleStateHistory = new ArticleStateHistory();
		$articleStateHistory->setWhoChanged($this->getUser());
		$articleStateHistory->setArticle($article);
		$articleStateHistory->setArticleState($articleState);
		$article->addArticleStatesHistory($articleStateHistory);
		$this->manager->save($article);
	}

}
