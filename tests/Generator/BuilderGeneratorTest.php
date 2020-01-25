<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Tests\Generator;

use PHPUnit\Framework\TestCase;
use Xoubaman\Builder\Generator\BuilderGenerator;
use Xoubaman\Builder\Generator\Converter\DefaultConverter;
use Xoubaman\Builder\Tests\Examples\Generator\SampleClass;

class BuilderGeneratorTest extends TestCase
{
    /** @test */
    public function generates_builder_when_using_the_default_converter(): void
    {
        $builderGenerator = new BuilderGenerator();
        $builderAsString  = $builderGenerator->forClassWithConverter(
            SampleClass::class,
            new DefaultConverter()
        );

        $path = __DIR__.'/../Examples/Generator/SampleClassBuilder.php';

        self::assertStringEqualsFile($path, $builderAsString);
    }
}
