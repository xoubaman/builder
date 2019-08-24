<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Tests\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Tester\CommandTester;
use Xoubaman\Builder\Command\FileAlreadyExists;
use Xoubaman\Builder\Command\GenerateBuilderCommand;
use Xoubaman\Builder\Generator\BuilderGenerator;

class GenerateBuilderCommandTest extends TestCase
{
    /** @var CommandTester */
    private $commandTester;

    protected function setUp(): void
    {
        parent::setUp();

        $this->commandTester = new CommandTester(
            new GenerateBuilderCommand(
                new BuilderGenerator()
            )
        );
    }

    public function testItRequiresClassnameAsParameter(): void
    {
        self::expectException(RuntimeException::class);
        $this->commandTester->execute([]);
    }

    public function testItCreatesAFileWithTheBuilder(): void
    {
        $this->commandTester->execute(['class' => EmptyClass::class]);
        $generatedBuilderPath = __DIR__.'/EmptyClassBuilder.php';
        self::assertFileExists($generatedBuilderPath);
        unlink($generatedBuilderPath);
    }

    public function testItCannotOverwriteExistingFiles(): void
    {
        self::expectException(FileAlreadyExists::class);
        $this->commandTester->execute(['class' => ClassWithBuilder::class]);
    }
}
