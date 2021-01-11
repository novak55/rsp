<?php declare(strict_types = 1);

namespace App\EventListener;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

final class EnterPage extends AbstractController
{

	/** @var SessionInterface */
	private $session;

	public function __construct(SessionInterface $session)
	{
		$this->session = $session;
	}

	public function __invoke(RequestEvent $event): void
	{
		if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
			return;
		}

		$password = 'RSP2020VSPJ';
		if (!($this->session->has('pwd') && $this->session->get('pwd') === $password)) {
			$this->session->set('pwd', $password);
		}

		if ($this->session->has('enterPage') && $this->session->get('enterPage') === true) {
			return;
		}

		if ($event->getRequest()->query->has('enterPage')) {
			if ($event->getRequest()->query->get('enterPage') === $this->session->get('pwd')) {
				$this->session->set('pwdEnterPageError', false);
				$this->session->set('enterPage', true);
			} else {
				$this->session->set('pwdEnterPageError', true);
			}
		} else {
			$this->session->set('pwdEnterPageError', false);
		}
	}

}
