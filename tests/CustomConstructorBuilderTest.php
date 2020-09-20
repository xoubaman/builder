<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Tests;

use PHPUnit\Framework\TestCase;
use Xoubaman\Builder\Tests\Examples\Builder\CustomConstructorFooBuilder;
use Xoubaman\Builder\Tests\Examples\Builder\FooBuilder;

class CustomConstructorBuilderTest extends TestCase
{
    /** @var CustomConstructorFooBuilder */
    private $withCustomConstructor;
    /** @var FooBuilder */
    private $withoutCustomConstructor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withCustomConstructor    = new CustomConstructorFooBuilder();
        $this->withoutCustomConstructor = new FooBuilder();
    }

    /** @test */
    public function uses_custom_constructor_when_defined(): void
    {
        $byRegularConstructor = $this->withoutCustomConstructor->build();
        self::assertFalse($byRegularConstructor->instantiatedByCustomConstructor());

        $byCustomConstructor = $this->withCustomConstructor->build();
        self::assertTrue($byCustomConstructor->instantiatedByCustomConstructor());
    }
}
