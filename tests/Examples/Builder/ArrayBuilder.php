<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Tests\Examples\Builder;

use Xoubaman\Builder\Builder;

final class ArrayBuilder extends Builder
{
    public const PARAM_1       = 'p1';
    public const PARAM_2       = 'p2';
    public const PARAM_1_VALUE = 'a value';
    public const PARAM_2_VALUE = 'another value';

    public function __construct()
    {
        $this->base = [
            self::PARAM_1 => self::PARAM_1_VALUE,
            self::PARAM_2 => self::PARAM_2_VALUE,
        ];
    }

    public function build(): array
    {
        return parent::build();
    }

    public function cloneLast(): array
    {
        return parent::cloneLast();
    }

    public function withoutParam1(): self
    {
        return $this->removeFromCurrent(self::PARAM_1);
    }

    public function withNestedParam2(string $nestedValue): self
    {
        $setup                = $this->currentSetup();
        $newParam2            = [$setup[self::PARAM_2] => $nestedValue];
        $setup[self::PARAM_2] = $newParam2;

        return $this->replaceCurrentSetup($setup);
    }
}
