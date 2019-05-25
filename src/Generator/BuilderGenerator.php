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

        $constructorArguments = $this->getConstructorArguments($reflection);

        return ClassBlock::generateClass(
            $namespace,
            $className,
            $constructorArguments
        );
    }

    private function getConstructorArguments(ReflectionClass $reflection): array
    {
        $properties = $reflection->getConstructor()->getParameters();

        $parsed = [];
        foreach ($properties as $property) {
            $type = $property->getType() ? $property->getType()->__toString() : 'null';

            $parsed[] = new Argument(
                $property->getName(),
                $type
            );
        }

        return $parsed;
    }
}
