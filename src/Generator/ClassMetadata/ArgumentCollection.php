<?php

declare(strict_types=1);

namespace Xoubaman\Builder\Generator\ClassMetadata;

final class ArgumentCollection
{
    /** @var array<Argument> */
    private readonly array $arguments;

    public function __construct(Argument ...$arguments)
    {
        $this->arguments = $arguments;
    }

    public function mapWith(callable $callable): mixed
    {
        return array_map($callable, $this->arguments);
    }
}
