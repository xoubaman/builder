<?php
declare(strict_types=1);

namespace Xoubaman\Builder;

use Xoubaman\Builder\Tests\Rebel;

abstract class Builder
{
    protected const CLASS_TO_BUILD = '';
    /** @var array */
    protected $lastBuilt = [];
    /** @var array */
    protected $current = [];
    /** @var array */
    protected $base = [];

    public function build()
    {
        return $this->doBuild();
    }

    public function cloneLast()
    {
        return $this->doCloneLast();
    }

    private function doBuild()
    {
        $this->current = $this->current + $this->base;

        $instance = $this->newInstanceWithParameters($this->current);

        $this->lastBuilt = $this->current;
        $this->current   = [];

        return $instance;
    }

    private function doCloneLast()
    {
        return $this->newInstanceWithParameters($this->lastBuilt);
    }

    private function newInstanceWithParameters(
        array $data
    ): Rebel {
        $class = static::CLASS_TO_BUILD;

        if (empty($class)) {
            throw ClassToBuildNotDefined::inBuilder($this);
        }

        return new $class(...\array_values($data));
    }

    final protected function addToCurrent(string $field, $value): void
    {
        if (empty($this->current)) {
            $this->current = $this->base;
        }

        $this->current[$field] = $value;
    }
}
