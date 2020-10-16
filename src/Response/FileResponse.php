<?php declare(strict_types = 1);

namespace App\Response;

use Symfony\Component\HttpFoundation\Response;

class FileResponse extends Response
{

	/** @var string */
	private $nazevSouboru;

	public function __construct(string $content, string $contentType, string $name)
	{
		$this->nazevSouboru = $name;
		$headers = [
			'Expires' => 'Mon, 26 Jul 1997 05:00:00 GMT',
			'Content-Type' => $contentType,
			'Content-Disposition' => 'attachement; filename="' . $name . '"',
			'Accept-Ranges' => 'bytes',
			'Cache-control' => 'private',
			'Pragma' => 'private',
		];

		parent::__construct($content, 200, $headers);
	}

	public function getNazevSouboru(): string
	{
		return $this->nazevSouboru;
	}

}
