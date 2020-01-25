<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Tests\Command;

use Xoubaman\Builder\Generator\ClassMetadata\ClassMetadata;
use Xoubaman\Builder\Generator\Converter;

final class VanilaConverter implements Converter
{
    public const OUTPUT = 'Actually I am not a big fan of vanila';

    public function __invoke(ClassMetadata $classMetadata): string
    {
        return self::OUTPUT;
    }
}
