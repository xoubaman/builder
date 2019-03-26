<?php
declare(strict_types=1);

namespace Xoubaman\Builder;

final class ClassToBuildNotDefined extends \Exception
{

    public static function inBuilder(Builder $builder): self
    {
        return new self(
            \sprintf(
                'Builder %s has no class to build defined',
                \get_class($builder)
            )
        );
    }
}
