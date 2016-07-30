<?php

namespace MolnApps\FileManager\Validator;

class TestValidator extends AbstractValidator
{
	private $allowedMimeTypes = [];
	private $allowedMaxSizeInKilobytes = 0;

	public function __construct(array $allowedMimeTypes = [], $allowedMaxSizeInKilobytes = 0)
	{
		$this->allowedMimeTypes = $allowedMimeTypes;
		$this->allowedMaxSizeInKilobytes = $allowedMaxSizeInKilobytes;
	}
	
	protected function getAllowedMimeTypes()
	{
		return $this->allowedMimeTypes;
	}

	protected function getAllowedMaxSizeInKilobytes()
	{
		return $this->allowedMaxSizeInKilobytes;
	}
}