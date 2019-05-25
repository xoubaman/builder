<?php

namespace Xoubaman\Builder\Tests\Generator;

use PHPUnit\Framework\TestCase;
use Xoubaman\Builder\Generator\BuilderGenerator;
use Xoubaman\Builder\Tests\Examples\Generator\PocoClass;

class BuilderGeneratorTest extends TestCase
{
    public function testBuilderIsGeneratedClassWithScalarArguments(): void
    {
        $builderGenerator = new BuilderGenerator();
        $builderAsString  = $builderGenerator->forClass(PocoClass::class);

        $expectedBuilder = file_get_contents(__DIR__.'/../Examples/Generator/PocoClassBuilder.php');

        self::assertEquals($expectedBuilder, $builderAsString);
    }

}
