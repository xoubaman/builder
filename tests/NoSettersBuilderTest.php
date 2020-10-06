<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Tests;

use PHPUnit\Framework\TestCase;
use Xoubaman\Builder\Tests\Examples\Builder\Foo;
use Xoubaman\Builder\Tests\Examples\Builder\FooBuilder;
use Xoubaman\Builder\Tests\Examples\Builder\NoSettersFooBuilder;

class NoSettersBuilderTest extends TestCase
{
    /** @var NoSettersFooBuilder */
    private $builder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->builder = new NoSettersFooBuilder();
    }

    /** @test */
    public function current_setup_is_changed_when_calling_magic_methods(): void
    {
        $instance = $this->builder
            ->withParam1('a kind')
            ->withParam2('of magic')
            ->withParam3(10)
            ->build();

        $expected = new Foo('a kind', 'of magic', 10);

        self::assertEquals($expected, $instance);
    }

    /** @test */
    public function fails_when_calling_magic_method_with_no_matching_key_in_current_setup(): void
    {
        $this->expectException(\OutOfBoundsException::class);
        $this->builder
            ->withRaccoon('this will fail')
            ->build();
    }
}
