<?php

namespace MolnApps\FileManager;

use \MolnApps\FileManager\Contracts\Storage;
use \MolnApps\FileManager\Contracts\UploadedFile;
use \MolnApps\FileManager\Contracts\Validator;

class FileManagerTest extends \PHPUnit_Framework_TestCase
{
	/** @test */
	public function it_can_be_instantiated()
	{
		$storage = $this->createMock(Storage::class);
		$validator = $this->createMock(Validator::class);
		
		$fileManager = new FileManager($storage, $validator);

		$this->assertNotNull($fileManager);
	}

	/** @test */
	public function it_puts_file_into_storage_if_valid()
	{
		$uploadedFile = $this->createMock(UploadedFile::class);
		$validator = $this->getValidatorMock($uploadedFile, true);
		$storage = $this->getStorageMock($uploadedFile, $this->once());
		
		$fileManager = new FileManager($storage, $validator);

		$fileManager->store($uploadedFile);
	}

	/** @test */
	public function it_wont_put_file_into_storage_if_not_valid()
	{
		$uploadedFile = $this->createMock(UploadedFile::class);
		$validator = $this->getValidatorMock($uploadedFile, false);
		$storage = $this->getStorageMock($uploadedFile, $this->never());
		
		$fileManager = new FileManager($storage, $validator);

		$fileManager->store($uploadedFile);
	}

	/** @test */
	public function it_will_return_validation_errors()
	{
		$storage = $this->createMock(Storage::class);
		$validator = $this->createMock(Validator::class);

		$validator
			->expects($this->once())
			->method('getErrors')
			->willReturn(['size' => 'Max file size exceeded']);

		$fileManager = new FileManager($storage, $validator);

		$this->assertEquals(['size' => 'Max file size exceeded'], $fileManager->getValidationErrors());
	}

	private function getValidatorMock($uploadedFile, $validates)
	{
		$validator = $this->createMock(Validator::class);

		$validator
			->expects($this->once())
            ->method('validate')
            ->with($this->equalTo($uploadedFile))
            ->willReturn($validates);

        return $validator;
	}

	private function getStorageMock($uploadedFile, $expects)
	{
		$storage = $this->createMock(Storage::class);
		
		$storage
			->expects($expects)
			->method('store')
			->with($this->equalTo($uploadedFile));

		return $storage;
	}
}