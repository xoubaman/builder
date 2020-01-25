<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Generator;

use Xoubaman\Builder\Generator\ClassMetadata\ClassMetadata;

interface Converter
{
    public function __invoke(ClassMetadata $classMetadata): string;
}
