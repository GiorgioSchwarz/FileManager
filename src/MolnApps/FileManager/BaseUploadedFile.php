<?php

namespace MolnApps\FileManager;

use \MolnApps\FileManager\Contracts\UploadedFile;

use \Illuminate\Http\UploadedFile as IlluminateUploadedFile;

class BaseUploadedFile implements UploadedFile
{
	private $uploadedFile;

	public function __construct(IlluminateUploadedFile $uploadedFile)
	{
		$this->uploadedFile = $uploadedFile;
	}

	public function getPath()
	{
		return $this->uploadedFile->path();
	}

	public function getMimeType()
	{
		return $this->uploadedFile->getMimeType();
	}

	public function getSize()
	{
		return $this->uploadedFile->getSize();
	}

	public function getExtension()
	{
		return $this->uploadedFile->guessExtension();
	}
}