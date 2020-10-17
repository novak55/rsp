<?php declare(strict_types = 1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function rand;

class IndexController extends AbstractController
{

	public function __construct()
	{
		/** odmazat až tady něco bude */
	}

	/**
	 * @Route("/", name="rsp")
	 * @return RedirectResponse|Response
	 */
	public function index()
	{
		$nahodneCislo = rand(0, 6);
		return $this->render('rsp/index.html.twig', [
			'twigVar' => $nahodneCislo,
		]);
	}

}
