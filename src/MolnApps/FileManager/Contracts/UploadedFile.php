<?php

namespace MolnApps\FileManager\Contracts;

interface UploadedFile
{
	public function getPath();
	public function getMimeType();
	public function getSize();
	public function getExtension();
}