<?php
declare(strict_types=1);

namespace Xoubaman\Builder;

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
        $this->current = $this->current + $this->base;

        $instance = $this->newInstanceWithParameters($this->current);

        $this->lastBuilt = $this->current;
        $this->current   = [];

        return $instance;
    }

    public function cloneLast()
    {
        return $this->newInstanceWithParameters($this->lastBuilt);
    }

    private function newInstanceWithParameters(
        array $data
    ) {
        $class = static::CLASS_TO_BUILD;

        if (empty($class)) {
            throw ClassToBuildNotDefined::inBuilder($this);
        }

        return new $class(...\array_values($data));
    }

    /** @return static */
    final protected function addToCurrent(string $field, $value)
    {
        if (empty($this->current)) {
            $this->current = $this->base;
        }

        $this->current[$field] = $value;

        return $this;
    }
}
