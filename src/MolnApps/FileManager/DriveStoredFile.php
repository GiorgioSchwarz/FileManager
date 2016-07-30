<?php

namespace MolnApps\FileManager;

use \MolnApps\FileManager\Contracts\StoredFile;
use \MolnApps\FileManager\Contracts\UploadedFile;

class DriveStoredFile implements StoredFile
{
	private $basePath;
	private $baseUrl;
	private $size;
	private $mimeType;
	private $filename;

	private $uniqueIdentifier;

	public function __construct(array $file)
	{
		$this->basePath = $file['basePath'];
		$this->baseUrl = $file['baseUrl'];
		$this->size = $file['size'];
		$this->mimeType = $file['mimeType'];
		$this->filename = $file['filename'];
		$this->uniqueIdentifier = $file['uniqueIdentifier'];
	}

	public static function createWithUploadedFile(UploadedFile $uploadedFile, $basePath, $baseUrl)
	{
		$uniqueIdentifier = time();

		return new static([
			'basePath' => $basePath,
			'baseUrl' => $baseUrl,
			'size' => $uploadedFile->getSize(),
			'mimeType' => $uploadedFile->getMimeType(),
			'filename' => $uniqueIdentifier . '.' . $uploadedFile->getExtension(),
			'uniqueIdentifier' => $uniqueIdentifier
		]);
	}

	public function __get($property)
	{
		return call_user_func([$this, 'get'.ucfirst($property)]);
	}

	public function toArray()
	{
		return [
			'permalink' => $this->permalink,
			'filename' => $this->filename,
			'size' => $this->size,
			'mimetype' => $this->mimetype,
		];
	}

	public function getPath()
	{
		return $this->getBasePath() . '/' . $this->filename;
	}

	protected function getBasePath()
	{
		return $this->basePath;
	}

	public function getPermalink()
	{
		return $this->getBaseUrl() . '/' . $this->filename;
	}

	protected function getBaseUrl()
	{
		return $this->baseUrl;
	}
	
	public function getFilename()
	{
		return $this->filename;
	}
	
	public function getSize()
	{
		return $this->size;
	}
	
	public function getMimeType()
	{
		return $this->mimeType;
	}

	public function getUniqueIdentifier()
	{
		return $this->uniqueIdentifier;
	}
}