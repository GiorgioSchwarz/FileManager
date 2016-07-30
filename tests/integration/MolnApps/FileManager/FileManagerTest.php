<?php

namespace MolnApps\FileManager\Integration;

use \MolnApps\FileManager\FileManager;

use \MolnApps\FileManager\Storage\Drive\TestDriveStorage;
use \MolnApps\FileManager\Validator\TestValidator;

use \MolnApps\FileManager\Contracts\UploadedFile;
use \MolnApps\FileManager\Contracts\StoredFile;

use \org\bovigo\vfs\vfsStream;

class FileManagerTest extends \PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
		$this->setupVfsStream();
	}

	private function setupVfsStream()
	{
		$root = vfsStream::setup('root');
		
		vfsStream::create([
			'tmp' => ['file.txt' => 'foobar'],
			'uploads' => ['image.png' => 'image'],
		]);

		$this->sourceUrl = vfsStream::url('root/tmp/file.txt');
		$this->destUrl = vfsStream::url('root/uploads');
		$this->existingUrl = vfsStream::url('root/uploads/image.png');
	}

	/** @test */
	public function it_can_be_instantiated_with_a_storage_and_a_validator()
	{
		$storage = new TestDriveStorage($this->destUrl, $this->destUrl);
		$validator = new TestValidator();

		$fileManager = new FileManager($storage, $validator);
	}

	/** @test */
	public function it_stores_valid_uploaded_file_and_returns_stored_file_instance()
	{
		$uploadedFile = $this->createUploadedFile();

		$fileManager = $this->createFileManager();

		$storedFile = $fileManager->store($uploadedFile);

		// Stored file is returned
		$this->assertInstanceOf(StoredFile::class, $storedFile);
	}

	/** @test */
	public function it_reads_a_previously_stored_file()
	{
		$storedFile = $this->createStoredFile();

		$fileManager = $this->createFileManager();

		$contents = $fileManager->get($storedFile);

		$this->assertEquals('image', $contents);
	}

	/** @test */
	public function it_stores_valid_uploaded_file_and_returns_populated_stored_file_instance()
	{
		$uploadedFile = $this->createUploadedFile();

		$fileManager = $this->createFileManager();

		$storedFile = $fileManager->store($uploadedFile);

		// Stored file has properties
		$this->assertEquals(1024, $storedFile->getSize());
		$this->assertEquals('plain/text', $storedFile->getMimeType());
		$this->assertContains($this->destUrl, $storedFile->getPath());
		$this->assertContains('.txt', $storedFile->getPath());

		// Stored file is read
		$this->assertEquals('foobar', $fileManager->get($storedFile));

		// Validation errors array is empty
		$this->assertEquals([], $fileManager->getValidationErrors());
	}

	/** @test */
	public function it_wont_store_uploaded_file_if_exceeds_max_size()
	{
		$uploadedFile = $this->createUploadedFile([
			'size' => 2048 * 1024,
		]);

		$fileManager = $this->createFileManager();

		$storedFile = $fileManager->store($uploadedFile);

		// Null is returned
		$this->assertNull($storedFile);

		// Validation errors array is not empty
		$this->assertEquals(
			['size' => 'Max allowed file size is 1048576 bytes. 2097152 bytes were uploaded.'], 
			$fileManager->getValidationErrors()
		);
	}

	/** @test */
	public function it_wont_store_uploaded_file_with_forbidden_mime_type()
	{
		$uploadedFile = $this->createUploadedFile([
			'type' => 'image/png',
		]);

		$fileManager = $this->createFileManager();

		$storedFile = $fileManager->store($uploadedFile);

		// Null is returned
		$this->assertNull($storedFile);

		// Validation errors array is not empty
		$this->assertEquals(
			['type' => 'Only allowed mime types are [plain/text]. [image/png] was provided.'], 
			$fileManager->getValidationErrors()
		);
	}

	/** @test */
	public function it_wont_store_uploaded_file_with_forbidden_mime_type_and_invalid_size()
	{
		$uploadedFile = $this->createUploadedFile([
			'size' => 2048 * 1024,
			'type' => 'image/png',
		]);

		$fileManager = $this->createFileManager();

		$storedFile = $fileManager->store($uploadedFile);

		// Null is returned
		$this->assertNull($storedFile);

		// Validation errors array is not empty
		$this->assertEquals(
			[
				'size' => 'Max allowed file size is 1048576 bytes. 2097152 bytes were uploaded.',
				'type' => 'Only allowed mime types are [plain/text]. [image/png] was provided.'
			], 
			$fileManager->getValidationErrors()
		);
	}

	private function createFileManager()
	{
		$storage = new TestDriveStorage($this->destUrl, $this->destUrl);
		$validator = new TestValidator(['plain/text'], 1024);

		return new FileManager($storage, $validator);
	}

	private function createUploadedFile(array $override = [])
	{
		$default = [
			'path' => $this->sourceUrl,
			'size' => 1024,
			'type' => 'plain/text',
			'extension' => 'txt',
		];

		$properties = array_merge($default, $override);

		$uploadedFile = $this->createMock(UploadedFile::class);
		$uploadedFile->method('getPath')->willReturn($properties['path']);
		$uploadedFile->method('getSize')->willReturn($properties['size']);
		$uploadedFile->method('getMimeType')->willReturn($properties['type']);
		$uploadedFile->method('getExtension')->willReturn($properties['extension']);

		return $uploadedFile;
	}

	private function createStoredFile(array $override = [])
	{
		$default = [
			'path' => $this->existingUrl,
		];

		$properties = array_merge($default, $override);

		$storedFile = $this->createMock(StoredFile::class);
		$storedFile->method('getPath')->willReturn($properties['path']);

		return $storedFile;
	}
}