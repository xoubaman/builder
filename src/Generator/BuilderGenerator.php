<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Generator;

use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflection\ReflectionParameter;
use Roave\BetterReflection\Reflection\ReflectionType;

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

    /** @return Argument[] */
    private function getConstructorArguments(ReflectionClass $reflection): array
    {
        $constructorParameters = $reflection->getConstructor()->getParameters();

        return array_map(
            $this->argumentFromReflectionParameterClosure(),
            $constructorParameters,
            []
        );
    }

    private function argumentFromReflectionParameterClosure(): \Closure
    {
        return function (ReflectionParameter $parameter): Argument {
            $type = $parameter->getType() instanceof ReflectionType ? $parameter->getType()->__toString() : 'null';

            return new Argument($parameter->getName(), $type);
        };
    }
}
