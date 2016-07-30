<?php

namespace MolnApps\FileManager\Contracts;

interface Storage
{
	public function store(UploadedFile $uploadedFile);
	public function get(StoredFile $storedFile);
}