<?php

namespace MolnApps\FileManager\Contracts;

interface StoredFile
{
	public function getPath();
	public function getPermalink();
	public function getFilename();
	public function getSize();
	public function getMimeType();

	public function getUniqueIdentifier();
}