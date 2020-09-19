<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Generator\ClassMetadata;

final class ArgumentCollection
{
    /** @var array<Argument> */
    private $arguments;

    public function __construct(Argument ...$arguments)
    {
        $this->arguments = $arguments;
    }

    /** @return mixed */
    public function mapWith(callable $callable)
    {
        return array_map($callable, $this->arguments);
    }
}
