<?php declare(strict_types = 1);

namespace App\EventListener;

use App\Service\MessageService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use function count;

final class MessengerHandler
{

	/** @var SessionInterface */
	private $session;

	/** @var TokenStorageInterface */
	private $securityToken;

	/** @var RouterInterface */
	private $router;

	private $messageService;

	public function __construct(
		SessionInterface $session,
		TokenStorageInterface $securityToken,
		RouterInterface $router,
		MessageService $messageService
	)
	{
		$this->session = $session;
		$this->securityToken = $securityToken;
		$this->router = $router;
		$this->messageService = $messageService;
	}

	public function __invoke(RequestEvent $event): void
	{
		if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
			return;
		}

		$this->session->start();
		$messages = $this->messageService->getMessages();
		if (count($messages) > 0) {
			foreach ($messages as $type => $message) {
				$this->session->getFlashBag()->set($type, $message);
			}
			return;
		}

		//      $this->securityToken->setToken(null);
	}

}
