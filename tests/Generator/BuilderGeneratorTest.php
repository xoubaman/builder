<?php

namespace Xoubaman\Builder\Tests\Generator;

use PHPUnit\Framework\TestCase;
use Xoubaman\Builder\Generator\BuilderGenerator;
use Xoubaman\Builder\Tests\Examples\Generator\SampleClass;

class BuilderGeneratorTest extends TestCase
{
    public function testBuilderIsGeneratedClassWithScalarArguments(): void
    {
        $builderGenerator = new BuilderGenerator();
        $builderAsString  = $builderGenerator->forClass(SampleClass::class);

        $path = __DIR__.'/../Examples/Generator/SampleClassBuilder.php';

        self::assertStringEqualsFile($path, $builderAsString);
    }
}
