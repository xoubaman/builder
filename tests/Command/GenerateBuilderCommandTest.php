<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Tests\Command;

use DateTime;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\Console\Tester\CommandTester;
use Xoubaman\Builder\Command\FileAlreadyExists;
use Xoubaman\Builder\Command\GenerateBuilderCommand;
use Xoubaman\Builder\Generator\BuilderGenerator;
use Xoubaman\Builder\Tests\Examples\Command\AlreadyHasOne;
use Xoubaman\Builder\Tests\Examples\Command\EmptyClass;

class GenerateBuilderCommandTest extends TestCase
{
    /** @var CommandTester */
    private $commandTester;
    /** @var InMemoryWriter */
    private $writer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->writer        = new InMemoryWriter();
        $this->commandTester = new CommandTester(
            new GenerateBuilderCommand(
                new BuilderGenerator(),
                $this->writer
            )
        );
    }

    /** @test */
    public function fails_when_no_classname_passed_as_parameter(): void
    {
        $this->expectException(RuntimeException::class);
        $this->commandTester->execute([]);
    }

    /** @test */
    public function fails_when_provided_classname_is_not_just_a_classname(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->commandTester->execute(
            [
                'class' => [
                    AlreadyHasOne::class,
                    AlreadyHasOne::class,
                ],
            ]
        );
    }

    /** @test */
    public function creates_a_builder_when_called_with_a_classname(): void
    {
        $this->commandTester->execute(['class' => EmptyClass::class]);

        $expectedBuilderPath = $this->buildPathToExampleFilesFor('EmptyClassBuilder.php');

        self::assertNotEmpty($this->writer->contentWrittenInPath($expectedBuilderPath));
    }

    /** @test */
    public function fails_when_builder_file_already_exists(): void
    {
        $this->writer->writeIn(
            $this->buildPathToExampleFilesFor('AlreadyHasOneBuilder.php'),
            'There is a file in this path'
        );
        $this->expectException(FileAlreadyExists::class);
        $this->commandTester->execute(['class' => AlreadyHasOne::class]);
    }

    /** @test */
    public function uses_concrete_converter_when_provided(): void
    {
        $this->commandTester->execute(
            [
                'class'     => EmptyClass::class,
                'converter' => VanilaConverter::class,
            ]
        );

        $path                    = $this->buildPathToExampleFilesFor('EmptyClassBuilder.php');
        $generatedBuilderContent = $this->writer->contentWrittenInPath($path);
        $expectedContent         = VanilaConverter::OUTPUT;
        self::assertEquals($expectedContent, $generatedBuilderContent);
    }

    /** @test */
    public function fails_when_provided_converter_is_not_a_converter(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->commandTester->execute(
            [
                'class'     => EmptyClass::class,
                'converter' => DateTime::class,
            ]
        );
    }

    private function buildPathToExampleFilesFor(string $filename): string
    {
        $pathTokens = [
            dirname(__DIR__),
            'Examples',
            'Command',
            $filename
        ];

        return implode(DIRECTORY_SEPARATOR, $pathTokens);
    }
}
