<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Tests;

use PHPUnit\Framework\TestCase;
use Xoubaman\Builder\Tests\Examples\Builder\Foo;
use Xoubaman\Builder\Tests\Examples\Builder\FooBuilder;
use Xoubaman\Builder\Tests\Examples\Builder\FooBuilderWithCallback;

class CallAfterBuildBuilderTest extends TestCase
{
    /** @var FooBuilderWithCallback */
    private $builderWithCall;
    /** @var FooBuilder */
    private $builderWithoutCall;

    protected function setUp(): void
    {
        parent::setUp();
        $this->builderWithCall    = new FooBuilderWithCallback();
        $this->builderWithoutCall = new FooBuilder();
    }

    /** @test */
    public function it_calls_method_after_build_when_defined(): void
    {
        $withoutCall = $this->builderWithoutCall->build();
        self::assertEquals(Foo::DEFAULT_AFTER_BUILD_METHOD_PARAM, $withoutCall->afterBuildMethodCalleParam());

        $withCallback = $this->builderWithCall->build();
        self::assertEquals(FooBuilderWithCallback::AFTER_BUILD_PARAM, $withCallback->afterBuildMethodCalleParam());
    }
}
