<?php declare(strict_types = 1);

namespace App\Service;

use App\Controller\ArticleController;
use App\Entity\FileAttachment;
use App\Entity\User;
use App\Repository\FileRepository;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use function in_array;

class FileDownloader
{

	/** @var string */
	private $targetDirectory;

	/** @var AuthorizationCheckerInterface */
	private $authorizationChecker;

	/** @var FileRepository */
	private $fileRepository;

	public function __construct($targetDirectory, AuthorizationCheckerInterface $authorizationChecker, FileRepository $fileRepository)
	{
		$this->targetDirectory = $targetDirectory;
		$this->authorizationChecker = $authorizationChecker;
		$this->fileRepository = $fileRepository;
	}

	/**
	 * @param FileAttachment $fileAttachment
	 * @return bool|string
	 */
	public function downLoad(FileAttachment $fileAttachment, ?User $user = null)
	{
		if (
			(
				$user === null
				&& in_array(
					$fileAttachment->getArticle()->getCurrentState()->getId(),
					ArticleController::PUBLIC_STATES,
					true
				)
			)
			||
			(
				$user !== null
				&&
				(
					(
						($this->authorizationChecker->isGranted('ROLE_SEFREDAKTOR')
							|| $this->authorizationChecker->isGranted('ROLE_REDAKTOR')
						)
						&& $fileAttachment->getArticle()->getCurrentState()->getId() > 1
						&& $fileAttachment->getArticle()->getCurrentState()->getId() !== 3
					)
					|| ($this->fileRepository->isUserReviewerOfArticle($user, $fileAttachment->getArticle())
						&& $fileAttachment->getArticle()->getCurrentState()->getId() === ArticleController::STAV_PREDANO_RECENZENTUM)
					|| ($this->authorizationChecker->isGranted('ROLE_AUTOR')
						&& $fileAttachment->getArticle()->getAuthor() === $user
						)
				)
			)
		) {
			$this->setSubfolder($fileAttachment->getArticle()
				->getId() . '/' . $fileAttachment->getFileSystemName());
			return $this->getTargetDirectory();
		}

		return false;
	}

	public function getTargetDirectory(): string
	{
		return $this->targetDirectory;
	}

	private function setSubfolder(string $subFolder): void
	{
		$this->targetDirectory .= '/' . $subFolder;
	}

}
