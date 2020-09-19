<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Tests\Command;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;
use PHPUnit\Framework\TestCase;
use Xoubaman\Builder\Command\FileAlreadyExists;
use Xoubaman\Builder\Command\FileSystemWriter;

class FileSystemWriterTest extends TestCase
{
    private const FILE_PATH               = 'root/the-file.php';
    private const EXISTING_FILE_NAME      = 'i-already-exist.php';
    private const FILE_PATH_EXISTING_FILE = 'root/i-already-exist.php';
    private const FILE_CONTENT            = 'weasel weasel';

    /** @var vfsStreamDirectory */
    private $root;
    /** @var FileSystemWriter */
    private $fileSystemWriter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->root = vfsStream::setup('root');
        vfsStream::newFile(self::EXISTING_FILE_NAME)
                 ->withContent('you cannot overwrite me')
                 ->at($this->root);

        $this->fileSystemWriter = new FileSystemWriter();
    }

    /** @test */
    public function creates_file_with_content_when_file_does_not_exist(): void
    {
        $file = vfsStream::url(self::FILE_PATH);
        $this->fileSystemWriter->writeIn($file, self::FILE_CONTENT);

        $urlToFile = $this->root->getChild(self::FILE_PATH);
        self::assertInstanceOf(vfsStreamFile::class, $urlToFile);
        self::assertEquals(self::FILE_CONTENT, $urlToFile->getContent());
    }

    /** @test */
    public function fails_when_file_exist(): void
    {
        $this->expectException(FileAlreadyExists::class);
        $file = vfsStream::url(self::FILE_PATH_EXISTING_FILE);
        $this->fileSystemWriter->writeIn($file, self::FILE_CONTENT);
    }
}
