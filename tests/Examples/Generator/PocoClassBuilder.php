<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Tests\Examples\Generator;

use Xoubaman\Builder\Builder;

final class PocoClassBuilder extends Builder
{
    protected const CLASS_TO_BUILD = PocoClass::class;

    public function __construct()
    {
    }

    public function build(): PocoClass
    {
        return parent::build();
    }

    public function cloneLast(): PocoClass
    {
        return parent::cloneLast();
    }
}
