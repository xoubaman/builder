<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Tests;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Xoubaman\Builder\Tests\Examples\Builder\ArrayBuilder;

class ArrayBuilderTest extends TestCase
{
    /** @var ArrayBuilder */
    private $arrayBuilder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->arrayBuilder = new ArrayBuilder();
    }

    #[Test]
    public function builds_array_instead_of_instance_when_no_class_defined(): void
    {
        $arrayBuilt = $this->arrayBuilder->build();

        $expected = [
            ArrayBuilder::PARAM_1 => ArrayBuilder::PARAM_1_VALUE,
            ArrayBuilder::PARAM_2 => ArrayBuilder::PARAM_2_VALUE,
        ];

        self::assertEquals($expected, $arrayBuilt);
    }

    #[Test]
    public function build_without_base_elements_when_removing_them(): void
    {
        $arrayBuilt = $this->arrayBuilder->withoutParam1()
                                         ->build();

        $expected = [
            ArrayBuilder::PARAM_2 => ArrayBuilder::PARAM_2_VALUE,
        ];

        self::assertEquals($expected, $arrayBuilt);
    }

    #[Test]
    public function is_able_to_build_when_modifying_nested_data(): void
    {
        $arrayBuilt = $this->arrayBuilder
            ->withNestedParam2('nested!')
            ->build();

        $expected = [
            ArrayBuilder::PARAM_1 => ArrayBuilder::PARAM_1_VALUE,
            ArrayBuilder::PARAM_2 => [ArrayBuilder::PARAM_2_VALUE => 'nested!'],
        ];

        self::assertEquals($expected, $arrayBuilt);
    }
}
