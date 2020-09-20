<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Tests;

use PHPUnit\Framework\TestCase;
use Xoubaman\Builder\Tests\Examples\Builder\Foo;
use Xoubaman\Builder\Tests\Examples\Builder\FooBuilder;
use Xoubaman\Builder\Tests\Examples\Builder\FooBuilderWithParamGenerators;

class ParamGeneratorBuilderTest extends TestCase
{
    /** @var FooBuilderWithParamGenerators */
    private $builder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->builder = new FooBuilderWithParamGenerators();
    }

    /** @test */
    public function builds_base_setup_when_called_without_setting_any_parameter(): void
    {
        $instance = $this->builder->build();

        $expected = new Foo(
            FooBuilderWithParamGenerators::PARAM_1_VALUE,
            FooBuilderWithParamGenerators::PARAM_2_VALUE,
            1
        );

        self::assertEquals($expected, $instance);
    }
}
