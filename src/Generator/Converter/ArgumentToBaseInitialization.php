<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Generator\Converter;

use Xoubaman\Builder\Generator\ClassMetadata\Argument;

final class ArgumentToBaseInitialization
{
    public function __invoke(Argument $argument): string
    {
        return sprintf(
            "self::%s => %s,",
            $argument->constantName(),
            $argument->default()
        );
    }
}
