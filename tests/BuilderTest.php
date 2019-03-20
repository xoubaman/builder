<?php

namespace Xoubaman\Builder\Tests;

use PHPUnit\Framework\TestCase;

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
        $instance = $this->builder->setName('Luke')
                                  ->setAddress('Tatooine Farm')
                                  ->setShip('X-Wing')
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
        $this->builder->setName('Luke')
                      ->setAddress('Tatooine Farm')
                      ->setShip('X-Wing')
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
        $this->builder->setName('Luke')
                      ->setAddress('Tatooine Farm')
                      ->setShip('X-Wing')
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
        $this->builder->setName('Luke')
                      ->setAddress('Tatooine Farm')
                      ->setShip('X-Wing')
                      ->build();

        $this->builder->setName('Leia')
                      ->setAddress('Endor Moon')
                      ->setShip('Bike');

        $this->builder->cloneLast();

        $instance = $this->builder->build();

        $expected = new Rebel(
            'Leia',
            'Endor Moon',
            'Bike'
        );

        self::assertEquals($instance, $expected);
    }
}
