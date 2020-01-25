<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Tests\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Tester\CommandTester;
use Xoubaman\Builder\Command\FileAlreadyExists;
use Xoubaman\Builder\Command\GenerateBuilderCommand;
use Xoubaman\Builder\Generator\BuilderGenerator;
use Xoubaman\Builder\Generator\Converter\DefaultConverter;

class GenerateBuilderCommandTest extends TestCase
{
    /** @var CommandTester */
    private $commandTester;
    /** @var string */
    private $generatedBuilderPath = '';

    protected function setUp(): void
    {
        parent::setUp();

        $this->commandTester = new CommandTester(
            new GenerateBuilderCommand(
                new BuilderGenerator()
            )
        );
    }

    protected function tearDown(): void
    {
        if (!empty($this->generatedBuilderPath)) {
            unlink($this->generatedBuilderPath); //How crappy is this? :D
        }

        parent::tearDown();
    }

    /** @test */
    public function fails_when_no_classname_passed_as_parameter(): void
    {
        self::expectException(RuntimeException::class);
        $this->commandTester->execute([]);
    }

    /** @test */
    public function creates_a_builder_when_called_with_a_classname(): void
    {
        $this->commandTester->execute(['class' => EmptyClass::class]);
        $this->generatedBuilderPath = __DIR__.'/EmptyClassBuilder.php';

        self::assertFileExists($this->generatedBuilderPath);
    }

    /** @test */
    public function fails_when_file_for_saving_the_builder_already_exists(): void
    {
        self::expectException(FileAlreadyExists::class);
        $this->commandTester->execute(['class' => ClassWithBuilder::class]);
    }

    /** @test */
    public function uses_the_provided_converter_when_there_is_one(): void
    {
        $this->commandTester->execute(
            [
                'class'     => EmptyClass::class,
                'converter' => VanilaConverter::class,
            ]
        );
        $this->generatedBuilderPath = __DIR__.'/EmptyClassBuilder.php';
        self::assertFileExists($this->generatedBuilderPath);

        $expectedContent = VanilaConverter::OUTPUT;
        self::assertEquals(
            $expectedContent,
            file_get_contents($this->generatedBuilderPath)
        );
    }

    /** @test */
    public function fails_when_provided_converter_is_not_a_converter(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->commandTester->execute(
            [
                'class'     => EmptyClass::class,
                'converter' => \DateTime::class,
            ]
        );
    }
}
