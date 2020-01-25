<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Generator;

use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflection\ReflectionParameter;
use Roave\BetterReflection\Reflection\ReflectionType;
use Xoubaman\Builder\Generator\ClassMetadata\Argument;
use Xoubaman\Builder\Generator\ClassMetadata\ClassMetadata;

final class BuilderGenerator
{
    public function forClassWithConverter(string $class, Converter $converter): string
    {
        $classMetadata = $this->getClassMetadata($class);

        return $converter($classMetadata);
    }

    private function getClassMetadata(string $class): ClassMetadata
    {
        $reflection           = ReflectionClass::createFromName($class);
        $constructorArguments = $this->getConstructorArguments($reflection);

        return new ClassMetadata(
            $reflection->getShortName(),
            $reflection->getNamespaceName(),
            ...$constructorArguments
        );
    }

    /** @return array<Argument> */
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
            $type = $parameter->getType() instanceof ReflectionType ?
                $parameter->getType()->__toString()
                : 'null';

            return new Argument($parameter->getName(), $type);
        };
    }
}
