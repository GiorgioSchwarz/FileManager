<?php

namespace MolnApps\FileManager\Storage\Drive;

class DriveStorage extends AbstractDriveStorage
{
	protected function move($sourcePath, $destinationPath)
	{
		move_uploaded_file($sourcePath, $destinationPath);
	}
}