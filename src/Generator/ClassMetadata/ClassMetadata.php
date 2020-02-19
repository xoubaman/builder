<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Generator\ClassMetadata;

final class ClassMetadata
{
    /** @var string */
    private $className;
    /** @var string */
    private $namespace;
    /** @var ArgumentCollection */
    private $arguments;

    public function __construct(
        string $className,
        string $namespace,
        Argument ...$constructorArguments
    ) {
        $this->className = $className;
        $this->namespace = $namespace;
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
