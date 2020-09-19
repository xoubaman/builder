<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Tests;

use PHPUnit\Framework\TestCase;
use Xoubaman\Builder\Tests\Examples\Builder\Rebel;
use Xoubaman\Builder\Tests\Examples\Builder\RebelArrayBuilder;
use Xoubaman\Builder\Tests\Examples\Builder\RebelBuilder;

class BuilderTest extends TestCase
{
    /** @var RebelBuilder */
    private $objectBuilder;
    /** @var RebelArrayBuilder */
    private $arrayBuilder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->objectBuilder = new RebelBuilder();
        $this->arrayBuilder  = new RebelArrayBuilder();
    }

    /** @test */
    public function builds_base_setup_when_called_without_setting_any_parameter(): void
    {
        $instance = $this->objectBuilder->build();

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
        $instance = $this->objectBuilder->withName('Luke')
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
        $this->objectBuilder->withName('Luke')
                            ->withAddress('Tatooine Farm')
                            ->withShip('X-Wing')
                            ->build();

        $instance = $this->objectBuilder->build();

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
        $this->objectBuilder->withName('Luke')
                            ->withAddress('Tatooine Farm')
                            ->withShip('X-Wing')
                            ->build();

        $instance = $this->objectBuilder->cloneLast();

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
        $this->objectBuilder->withName('Luke')
                            ->withAddress('Tatooine Farm')
                            ->withShip('X-Wing')
                            ->build();

        $this->objectBuilder->withName('Leia')
                            ->withAddress('Endor Moon')
                            ->withShip('Bike');

        $this->objectBuilder->cloneLast();

        $instance = $this->objectBuilder->build();

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
        $arrayBuilt = $this->arrayBuilder->build();

        $expected = [
            'name'    => 'Han Solo',
            'address' => 'Tatooine',
            'ship'    => 'Millennium Falcon',
        ];

        self::assertEquals($expected, $arrayBuilt);
    }

    /** @test */
    public function build_without_base_elements_when_removing_them(): void
    {
        $arrayBuilt = $this->arrayBuilder->withoutShip()
                                         ->build();

        $expected = [
            'name'    => 'Han Solo',
            'address' => 'Tatooine',
        ];

        self::assertEquals($expected, $arrayBuilt);
    }

    /** @test */
    public function is_able_to_build_when_modifying_nested_data(): void
    {
        $arrayBuilt = $this->arrayBuilder
            ->withNestedAddress('cantina')
            ->build();

        $expected = [
            'name'    => 'Han Solo',
            'address' => ['Tatooine' => 'cantina'],
            'ship'    => 'Millennium Falcon',
        ];

        self::assertEquals($expected, $arrayBuilt);
    }
}
