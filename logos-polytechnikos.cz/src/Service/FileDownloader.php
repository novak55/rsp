<?php declare(strict_types = 1);

namespace App\Service;

use App\Controller\ArticleController;
use App\Entity\FileAttachment;
use App\Entity\Setting;
use App\Entity\TepmlateHistory;
use App\Entity\User;
use App\Repository\FileRepository;
use App\Repository\SettingsRepository;
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

	/** @var Setting */
	private $setting;

	public function __construct(
		$targetDirectory,
		AuthorizationCheckerInterface $authorizationChecker,
		FileRepository $fileRepository,
		SettingsRepository $settingsRepository
	)
	{
		$this->targetDirectory = $targetDirectory;
		$this->authorizationChecker = $authorizationChecker;
		$this->fileRepository = $fileRepository;
		$this->setting = $settingsRepository->getSettings();
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
						(
							$this->authorizationChecker->isGranted('ROLE_SEFREDAKTOR')
							||
							$this->authorizationChecker->isGranted('ROLE_REDAKTOR')
						)
						&& $fileAttachment->getArticle()->getCurrentState()->getId() > 1
						&& $fileAttachment->getArticle()->getCurrentState()->getId() !== 3
					)
					||
					(
						$this->fileRepository->isUserReviewerOfArticle($user, $fileAttachment->getArticle())
						&& $fileAttachment->getArticle()->getCurrentState()->getId() === ArticleController::STAV_PREDANO_RECENZENTUM)
					||
					(
						$fileAttachment->getArticle()->getAuthor() === $user
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

	/**
	 * @param TepmlateHistory $template
	 * @param User|null $user
	 * @return false|string
	 */
	public function templateDownLoad(TepmlateHistory $template, ?User $user = null)
	{
		if (
			$template->getId() === $this->setting->getActiveTemplate()->getId()
			||
			(
				$user !== null
				&&
				(
					(
						$this->authorizationChecker->isGranted('ROLE_SEFREDAKTOR')
						||
						$this->authorizationChecker->isGranted('ROLE_REDAKTOR')
					)
					&& $template->getArticleTemplate()->getArticle() === null
				)
			)
		) {
			$this->setSubfolder('templates/' . $template->getDate()->format('Y-m-d') . '/' . $template->getArticleTemplate()->getFileSystemName());
			return $this->getTargetDirectory();
		}

		return false;
	}

	private function setSubfolder(string $subFolder): void
	{
		$this->targetDirectory .= '/' . $subFolder;
	}

}
