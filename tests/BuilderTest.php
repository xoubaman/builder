<?php

namespace Xoubaman\Builder\Tests;

use PHPUnit\Framework\TestCase;
use Xoubaman\Builder\Builder;
use Xoubaman\Builder\ClassToBuildNotDefined;
use Xoubaman\Builder\Tests\Examples\Builder\Rebel;
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

    public function testItReturnsAnInstanceWithDefaultValues(): void
    {
        $instance = $this->builder->build();

        $expected = new Rebel(
            RebelBuilder::DEFAULT_NAME,
            RebelBuilder::DEFAULT_ADDRESS,
            RebelBuilder::DEFAULT_SHIP
        );

        self::assertEquals($instance, $expected);
    }

    public function testSetPropertiesAreUsedForNewInstance(): void
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

    public function testAfterABuiltPropertiesAreResetToDefaults(): void
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

        self::assertEquals($instance, $expected);
    }

    public function testLastBuiltInstanceCanBeCloned(): void
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

        self::assertEquals($instance, $expected);
    }

    public function testCloningLastBuiltWhileSettingNewValuesDoNotAffectCurrentBuild(): void
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

        self::assertEquals($instance, $expected);
    }

    public function testExceptionThrownIfNoClassToBuildDefined(): void
    {
        $builder = new class extends Builder
        {
        };
        $this->expectException(ClassToBuildNotDefined::class);
        $builder->build();
    }
}
