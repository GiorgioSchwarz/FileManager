<?php

namespace MolnApps\FileManager\Contracts;

interface Validator
{
	public function validate(UploadedFile $uploadedFile);
	public function getErrors();
}