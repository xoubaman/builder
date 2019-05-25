<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Generator;

use Roave\BetterReflection\Reflection\ReflectionClass;

final class BuilderGenerator
{
    public function forClass(string $class): string
    {
        $reflection = ReflectionClass::createFromName($class);
        $className  = $reflection->getShortName();
        $namespace  = $reflection->getNamespaceName();

        return ClassBlock::generateClass($namespace, $className);
    }
}
