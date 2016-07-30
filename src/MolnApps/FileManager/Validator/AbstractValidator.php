<?php

namespace MolnApps\FileManager\Validator;

use \MolnApps\FileManager\Contracts\Validator;
use \MolnApps\FileManager\Contracts\UploadedFile;

abstract class AbstractValidator implements Validator
{
	private $errors = [];

	private function getAllowedMaxSize()
	{
		return 1024 * $this->getAllowedMaxSizeInKilobytes();
	}

	abstract protected function getAllowedMimeTypes();
	abstract protected function getAllowedMaxSizeInKilobytes();

	public function validate(UploadedFile $uploadedFile)
	{
		if ( ! $this->allowedSize($uploadedFile)) {
			$this->addError(
				'size', 
				'Max allowed file size is ' . $this->getAllowedMaxSize() . ' bytes. ' . 
				$uploadedFile->getSize() . ' bytes were uploaded.'
			);
		}

		if ( ! $this->allowedType($uploadedFile)) {
			$this->addError(
				'type', 
				'Only allowed mime types are [' . implode(', ', $this->getAllowedMimeTypes()) . ']. ' .
				'[' . $uploadedFile->getMimeType() .'] was provided.'
			);
		}

		return ! $this->errors;
	}

	private function allowedSize(UploadedFile $uploadedFile)
	{
		return ! $this->getAllowedMaxSize() || $uploadedFile->getSize() <= $this->getAllowedMaxSize();
	}

	private function allowedType(UploadedFile $uploadedFile)
	{
		return ! $this->getAllowedMimeTypes() || in_array($uploadedFile->getMimeType(), $this->getAllowedMimeTypes());
	}

	private function addError($identifier, $message)
	{
		$this->errors[$identifier] = $message;
	}

	public function getErrors()
	{
		return $this->errors;
	}
}