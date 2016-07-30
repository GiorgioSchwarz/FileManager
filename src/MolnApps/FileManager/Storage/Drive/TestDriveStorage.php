<?php

namespace MolnApps\FileManager\Storage\Drive;

class TestDriveStorage extends AbstractDriveStorage
{
	protected function move($sourcePath, $destinationPath)
	{
		// vfsStream, used for testing, does not support move_uploaded_file();
		copy($sourcePath, $destinationPath);
	}
}