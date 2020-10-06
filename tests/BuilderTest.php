<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Tests;

use PHPUnit\Framework\TestCase;
use Xoubaman\Builder\Tests\Examples\Builder\Foo;
use Xoubaman\Builder\Tests\Examples\Builder\FooBuilder;
use Xoubaman\Builder\Tests\Examples\Builder\NoSettersFooBuilder;

class BuilderTest extends TestCase
{
    /** @var FooBuilder */
    private $builder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->builder = new FooBuilder();
    }

    /** @test */
    public function builds_base_setup_when_called_without_setting_any_parameter(): void
    {
        $instance = $this->builder->build();

        $expected = new Foo(
            FooBuilder::PARAM_1_VALUE,
            FooBuilder::PARAM_2_VALUE,
            FooBuilder::PARAM_3_VALUE
        );

        self::assertEquals($expected, $instance);
    }

    /** @test */
    public function builds_configured_setup_when_setting_parameters(): void
    {
        $instance = $this->builder->withParam1('Luke')
                                  ->withParam2('Skywalker')
                                  ->withParam3(666)
                                  ->build();

        $expected = new Foo('Luke', 'Skywalker', 666);

        self::assertEquals($expected, $instance);
    }

    /** @test */
    public function builds_base_setup_when_building_after_a_previous_customized_setup_build(): void
    {
        $this->builder->withParam1('Luke')
                      ->withParam2('Skywalker')
                      ->withParam3(666)
                      ->build();

        $instance = $this->builder->build();

        $expected = new Foo(
            FooBuilder::PARAM_1_VALUE,
            FooBuilder::PARAM_2_VALUE,
            FooBuilder::PARAM_3_VALUE
        );

        self::assertEquals($expected, $instance);
    }

    /** @test */
    public function builds_last_setup_when_cloning(): void
    {
        $this->builder->withParam1('Luke')
                      ->withParam2('Skywalker')
                      ->withParam3(666)
                      ->build();

        $instance = $this->builder->cloneLast();

        $expected = new Foo('Luke', 'Skywalker', 666);

        self::assertEquals($expected, $instance);
    }

    /** @test */
    public function last_setup_can_be_recovered_after_building(): void
    {
        $firstInstance = $this->builder->withParam1('Luke')
                                       ->withParam2('Skywalker')
                                       ->withParam3(666)
                                       ->build();

        $secondInstance = $this->builder->repeatLastSetup()->build();

        self::assertEquals($firstInstance, $secondInstance);
    }

    /** @test */
    public function current_setup_is_not_affected_when_cloning(): void
    {
        $this->builder->withParam1('Luke')
                      ->withParam2('Skywalker')
                      ->withParam3(666)
                      ->build();

        $this->builder->withParam1('Leia')
                      ->withParam2('Endor Moon')
                      ->withParam3(999);

        $this->builder->cloneLast();

        $instance = $this->builder->build();

        $expected = new Foo('Leia', 'Endor Moon', 999);

        self::assertEquals($expected, $instance);
    }
}
