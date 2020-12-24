<?php declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Review;
use App\Entity\User;
use App\Form\AddReviewerArticleType;
use App\Form\AdvancedUserRegisterType;
use App\Form\UserProfileType;
use App\Form\UserRegisterType;
use App\Manager\RspManager;
use App\Repository\ArticleRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class UserController extends AbstractController
{

	public const ROLE_AUTOR = 2;

	/** @var FlashBagInterface */
	private $flashBag;

	/** @var RspManager */
	private $manager;

	/** @var UserRepository */
	private $userRepository;

	public function __construct(FlashBagInterface $flashBag, RspManager $manager, UserRepository $userRepository)
	{
		$this->flashBag = $flashBag;
		$this->manager = $manager;
		$this->userRepository = $userRepository;
	}

	/**
	 * @Route("/register/author")
	 * @param Request $request
	 * @return RedirectResponse|Response
	 */
	public function registerAuthor(Request $request, FormFactoryInterface $formFactory)
	{
		$user = new User();
		$form = $formFactory->create(UserRegisterType::class, $user);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			try {
				$user->setRole($this->userRepository->getRoleById(self::ROLE_AUTOR));
				$this->manager->saveUser($user);
				$this->flashBag->add('success', 'Byl jste úspěšně zaregistrován jako autor. Můžete se přihlásit.');
				return new RedirectResponse($this->generateUrl('login'));
			} catch (Throwable $e) {
				$this->flashBag->add('warning', 'Nepodařilo se vytvořit účet kontaktujte administrátora webu.');
			}
		}
		return $this->render('user/register.html.twig', [
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/register/redaktor")
	 * @param Request $request
	 * @return RedirectResponse|Response
	 */
	public function registerEditor(Request $request, FormFactoryInterface $formFactory)
	{
		if (!$this->isGranted('ROLE_REDAKTOR')) {
			return $this->render('security/secerr.html.twig');
		}

		$user = new User();
		$form = $formFactory->create(AdvancedUserRegisterType::class, $user);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			try {
				$this->manager->saveUser($user);
				$this->flashBag->add('success', 'Uživatel byl úspěšně vytvořen.');
				return new RedirectResponse($this->generateUrl('rsp'));
			} catch (Throwable $e) {
				$this->flashBag->add('warning', 'Nepodařilo se vytvořit účet kontaktujte administrátora webu.');
			}
		}
		return $this->render('user/register.html.twig', [
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/add-reviewer-article/{article}")
	 * @param Request $request
	 * @return Response|RedirectResponse
	 */
	public function addReviewerArticle(
		Request $request,
		Article $article,
		ArticleRepository $articleRepository,
		ReviewRepository $reviewRepository
	)
	{
		if (!$this->isGranted('ROLE_REDAKTOR')) {
			return $this->render('security/secerr.html.twig');
		}
		$review = new Review();
		$form = $this->createForm(AddReviewerArticleType::class, $review, ['article' => $article]);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$this->flashBag->add('success', 'Recenzent ' . $review->getReviewer()->getFullNameByName() . ' byl úspěšně přiřazen k článku ' . $article->getName() . '.');
			$review->setArticle($article);
			$review->setReviewState($reviewRepository->getStateReviewById(ReviewController::ROZPRACOVANO));
			$this->manager->add($review);
			$this->manager->changeArticleState($article, $articleRepository->getStateArticleById(ArticleController::STAV_PREDANO_RECENZENTUM), $this->getUser());
			return new RedirectResponse($this->generateUrl('show_article_detail', array('article' => $article->getId())));
		}

		return $this->render('user/add_reviewer_article.html.twig', [
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/change-profile/{user}")
	 * @param Request $request
	 * @return RedirectResponse|Response
	 */
	public function changeProfile(Request $request, ?User $user = null)
	{
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		if ($user !== null && !$this->isGranted('ROLE_REDAKTOR')) {
			return $this->render('security/secerr.html.twig');
		}
		if ($user === null) {
			$user = $this->getUser();
		}
		$url = $request->query->has('url') ? $request->query->get('url') : $this->generateUrl('rsp');
		$form = $this->createForm(UserProfileType::class, $user);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$this->manager->saveUser($user, $this->getUser()->getPassword());
			$this->flashBag->add('success', $this->getUser()->getId() === $user->getId() ? 'Váš profil byl upraven.' : 'Profil uživatele ' . $user->getFullNameByName() . ' byl upraven.');
			return new RedirectResponse($url);
		}
		return $this->render('user/register.html.twig', [
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/user-list")
	 * @return Response
	 */
	public function userList(): Response
	{
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		if (!($this->isGranted('ROLE_REDAKTOR') || $this->isGranted('ROLE_SEFREDAKTOR'))) {
			return $this->render('security/secerr.html.twig');
		}
		return $this->render('user/user_list.html.twig', [
			'users' => $this->userRepository->getUsers(),
		]);
	}

	/**
	 * @Route("/user-profile/{user}")
	 * @param User $user
	 * @return Response
	 */
	public function userProfile(User $user): Response
	{
		if (!($this->isGranted('ROLE_REDAKTOR') || $this->isGranted('ROLE_SEFREDAKTOR'))) {
			return $this->render('security/secerr.html.twig');
		}
		return $this->render('user/user_profile.html.twig', [
			'user' => $user,
		]);
	}
    
}
