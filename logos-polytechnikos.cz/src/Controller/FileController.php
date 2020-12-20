<?php declare(strict_types = 1);

namespace App\Controller;

use App\Entity\FileAttachment;
use App\Entity\TepmlateHistory;
use App\Response\FileResponse;
use App\Service\FileDownloader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use function file_get_contents;
use function is_file;
use function is_readable;

/**
 * @Route("/file")
 */
class FileController extends AbstractController
{

	/** @var FileDownloader */
	private $fileDownloader;

	/** @var FlashBagInterface */
	private $flashBag;

	public function __construct(FileDownloader $fileDownloader, FlashBagInterface $flashBag)
	{
		$this->fileDownloader = $fileDownloader;
		$this->flashBag = $flashBag;
	}

	/**
	 * @Route("/download-article/{fileAttachment}")
	 * @return RedirectResponse|FileResponse
	 */
	public function downloadAttachment(FileAttachment $fileAttachment)
	{
		$downLoad = $this->fileDownloader->downLoad($fileAttachment, $this->getUser());
		if ($downLoad === false) {
			$this->flashBag->add('danger', 'Chyba: Pro stažení souboru nemáte oprávnění!');
			return new RedirectResponse($this->generateUrl('rsp'));
		}

		if (!is_readable($downLoad) || !is_file($downLoad)) {
			$this->flashBag->add('danger', 'Chyba: Požadovaný soubor nebyl nalezen!');
			return new RedirectResponse($this->generateUrl('rsp'));
		}

		$soubor = file_get_contents($downLoad);
		return new FileResponse($soubor, $fileAttachment->getMimeType(), $fileAttachment->getFileName());
	}

	/**
	 * @Route("/download-template/{template}")
	 * @param TepmlateHistory $template
	 * @return FileResponse|RedirectResponse
	 */
	public function downloadTemplate(TepmlateHistory $template)
	{
		$downLoad = $this->fileDownloader->templateDownLoad($template, $this->getUser());
		if ($downLoad === false) {
			$this->flashBag->add('danger', 'Chyba: Pro stažení souboru nemáte oprávnění!');
			return new RedirectResponse($this->generateUrl('rsp'));
		}

		if (!is_readable($downLoad) || !is_file($downLoad)) {
			$this->flashBag->add('danger', 'Chyba: Požadovaný soubor nebyl nalezen!');
			return new RedirectResponse($this->generateUrl('rsp'));
		}
		$soubor = file_get_contents($downLoad);
		return new FileResponse($soubor, $template->getArticleTemplate()->getMimeType(), $template->getArticleTemplate()->getFileName());
	}

}
