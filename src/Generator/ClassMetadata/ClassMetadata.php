<?php

declare(strict_types=1);

namespace Xoubaman\Builder\Generator\ClassMetadata;

final class ClassMetadata
{
    private readonly ArgumentCollection $arguments;

    public function __construct(
        private readonly string $className,
        private readonly string $namespace,
        Argument ...$constructorArguments
    ) {
        $this->arguments = new ArgumentCollection(...$constructorArguments);
    }

    public function className(): string
    {
        return $this->className;
    }

    public function namespace(): string
    {
        return $this->namespace;
    }

    public function arguments(): ArgumentCollection
    {
        return $this->arguments;
    }
}
