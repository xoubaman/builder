<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Tests\Examples\Builder;

use Xoubaman\Builder\Builder;

final class FooBuilder extends Builder
{
    public const PARAM_1_VALUE = 'a value';
    public const PARAM_2_VALUE = 'some other value';
    public const PARAM_3_VALUE = 8;

    private const PARAM_1 = 'param1';
    private const PARAM_2 = 'param2';
    private const PARAM_3 = 'param3';

    protected const CLASS_TO_BUILD = Foo::class;

    public function __construct()
    {
        $this->base = [
            self::PARAM_1 => self::PARAM_1_VALUE,
            self::PARAM_2 => self::PARAM_2_VALUE,
            self::PARAM_3 => self::PARAM_3_VALUE,
        ];
    }

    public function build(): Foo
    {
        return parent::build();
    }

    public function cloneLast(): Foo
    {
        return parent::cloneLast();
    }

    public function withParam1(string $param1): self
    {
        return $this->addToCurrent(self::PARAM_1, $param1);
    }

    public function withParam2(string $param2): self
    {
        return $this->addToCurrent(self::PARAM_2, $param2);
    }

    public function withParam3(int $param3): self
    {
        return $this->addToCurrent(self::PARAM_3, $param3);
    }
}
