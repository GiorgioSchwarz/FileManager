<?php

namespace MolnApps\FileManager;

use \MolnApps\FileManager\Contracts\Storage;
use \MolnApps\FileManager\Contracts\StoredFile;
use \MolnApps\FileManager\Contracts\UploadedFile;
use \MolnApps\FileManager\Contracts\Validator;

class FileManager
{
	private $storage;

	public function __construct(Storage $storage, Validator $validator)
	{
		$this->storage = $storage;
		$this->validator = $validator;
	}

	public function store(UploadedFile $uploadedFile)
	{
		if ($this->validator->validate($uploadedFile)) {
			return $this->storage->store($uploadedFile);
		}
	}

	public function get(StoredFile $storedFile)
	{
		return $this->storage->get($storedFile);
	}

	public function getValidationErrors()
	{
		return $this->validator->getErrors();
	}
}