<?php

namespace MolnApps\FileManager\Storage\Drive;

use \MolnApps\FileManager\Contracts\Storage;
use \MolnApps\FileManager\Contracts\StoredFile;
use \MolnApps\FileManager\Contracts\UploadedFile;

use \MolnApps\FileManager\DriveStoredFile;

abstract class AbstractDriveStorage implements Storage
{
	public function __construct($basePath, $baseUrl)
	{
		$this->basePath = $basePath;
		$this->baseUrl = $baseUrl;
	}

	public function store(UploadedFile $uploadedFile)
	{
		$storedFile = DriveStoredFile::createWithUploadedFile($uploadedFile, $this->basePath, $this->baseUrl);

		$this->move($uploadedFile->getPath(), $storedFile->getPath());

		return $storedFile;
	}

	abstract protected function move($sourcePath, $destinationPath);

	public function get(StoredFile $storedFile)
	{
		return file_get_contents($storedFile->getPath());
	}
}