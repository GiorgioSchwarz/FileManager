<?php

namespace MolnApps\FileManager\Validator;

class NullValidator extends AbstractValidator
{
	protected function getAllowedMimeTypes()
	{
		return [];
	}

	protected function getAllowedMaxSizeInKilobytes()
	{
		return 0;
	}
}