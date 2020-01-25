<?php

namespace Xoubaman\Builder\Tests;

use PHPUnit\Framework\TestCase;
use Xoubaman\Builder\Tests\Examples\Builder\Rebel;
use Xoubaman\Builder\Tests\Examples\Builder\RebelArrayBuilder;
use Xoubaman\Builder\Tests\Examples\Builder\RebelBuilder;

class BuilderTest extends TestCase
{
    /** @var RebelBuilder */
    private $builder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->builder = new RebelBuilder();
    }

    /** @test */
    public function builds_base_setup_when_called_without_setting_any_parameter(): void
    {
        $instance = $this->builder->build();

        $expected = new Rebel(
            RebelBuilder::DEFAULT_NAME,
            RebelBuilder::DEFAULT_ADDRESS,
            RebelBuilder::DEFAULT_SHIP
        );

        self::assertEquals($expected, $instance);
    }

    /** @test */
    public function builds_configured_setup_when_setting_parameters(): void
    {
        $instance = $this->builder->withName('Luke')
                                  ->withAddress('Tatooine Farm')
                                  ->withShip('X-Wing')
                                  ->build();

        $expected = new Rebel(
            'Luke',
            'Tatooine Farm',
            'X-Wing'
        );

        self::assertEquals($expected, $instance);
    }

    /** @test */
    public function builds_base_setup_when_building_after_a_previous_customized_setup_build(): void
    {
        $this->builder->withName('Luke')
                      ->withAddress('Tatooine Farm')
                      ->withShip('X-Wing')
                      ->build();

        $instance = $this->builder->build();

        $expected = new Rebel(
            RebelBuilder::DEFAULT_NAME,
            RebelBuilder::DEFAULT_ADDRESS,
            RebelBuilder::DEFAULT_SHIP
        );

        self::assertEquals($expected, $instance);
    }

    /** @test */
    public function builds_last_setup_when_cloning(): void
    {
        $this->builder->withName('Luke')
                      ->withAddress('Tatooine Farm')
                      ->withShip('X-Wing')
                      ->build();

        $instance = $this->builder->cloneLast();

        $expected = new Rebel(
            'Luke',
            'Tatooine Farm',
            'X-Wing'
        );

        self::assertEquals($expected, $instance);
    }

    /** @test */
    public function current_setup_is_not_affected_when_cloning(): void
    {
        $this->builder->withName('Luke')
                      ->withAddress('Tatooine Farm')
                      ->withShip('X-Wing')
                      ->build();

        $this->builder->withName('Leia')
                      ->withAddress('Endor Moon')
                      ->withShip('Bike');

        $this->builder->cloneLast();

        $instance = $this->builder->build();

        $expected = new Rebel(
            'Leia',
            'Endor Moon',
            'Bike'
        );

        self::assertEquals($expected, $instance);
    }

    /** @test */
    public function builds_array_instead_of_intance_when_no_class_defined(): void
    {
        $builder = new RebelArrayBuilder();

        $arrayBuilt = $builder->build();

        $expected = [
            'here'    => 'Han Solo',
            'address' => 'Tatooine',
            'ship'    => 'Millennium Falcon',
        ];

        self::assertEquals($expected, $arrayBuilt);
    }

    /** @test */
    public function build_without_base_elements_when_removing_them(): void
    {
        $builder = new RebelArrayBuilder();

        $arrayBuilt = $builder->withoutShip()
                              ->build();

        $expected = [
            'here'    => 'Han Solo',
            'address' => 'Tatooine',
        ];

        self::assertEquals($expected, $arrayBuilt);
    }
}
