<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Generator\Converter;

use Xoubaman\Builder\Generator\ClassMetadata\Argument;

final class ArgumentToConstantDeclaration
{
    public function __invoke(Argument $argument): string
    {
        return sprintf(
            "private const %s = '%s';",
            $argument->constantName(),
            $argument->name()
        );
    }
}
