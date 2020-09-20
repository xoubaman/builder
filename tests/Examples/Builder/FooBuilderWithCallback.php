<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Tests\Examples\Builder;

use Xoubaman\Builder\Builder;

final class FooBuilderWithCallback extends Builder
{
    public const AFTER_BUILD_PARAM = 'after build was called';

    protected const CLASS_TO_BUILD   = Foo::class;
    protected const AFTER_BUILD_CALL = ['afterBuildMethod', self::AFTER_BUILD_PARAM];

    public function __construct()
    {
        $this->base = [
            'param1' => 'some value',
            'param2' => 'some other value',
            'param3' => 4,
        ];
    }

    public function build(): Foo
    {
        return parent::build();
    }
}
