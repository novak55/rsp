<?php declare(strict_types = 1);

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use function pathinfo;
use function transliterator_transliterate;
use function uniqid;
use const PATHINFO_FILENAME;

class FileUploader
{

	/** @var string */
	private $targetDirectory;

	public function __construct($targetDirectory)
	{
		$this->targetDirectory = $targetDirectory;
	}

	public function setSubfolder(string $subFolder): void
	{
		$this->targetDirectory .= '/' . $subFolder;
	}

	public function upload(UploadedFile $file): string
	{
		$originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
		$safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
		$fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

		try {
			$file->move($this->getTargetDirectory(), $fileName);
		} catch (FileException $e) {
			// never ever happend :D
		}

		return $fileName;
	}

	public function getTargetDirectory(): string
	{
		return $this->targetDirectory;
	}

}
