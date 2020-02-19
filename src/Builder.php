<?php
declare(strict_types=1);

namespace Xoubaman\Builder;

abstract class Builder
{
    protected const CLASS_TO_BUILD = '';

    protected $lastBuilt = [];
    protected $current   = [];
    protected $base      = [];

    public function build()
    {
        $this->initCurrentIfNotYet();
        $instance        = $this->newInstanceWithParameters($this->current);
        $this->lastBuilt = $this->current;
        $this->current   = [];

        return $instance;
    }

    public function cloneLast()
    {
        return $this->newInstanceWithParameters($this->lastBuilt);
    }

    private function newInstanceWithParameters(array $data)
    {
        $class = static::CLASS_TO_BUILD;

        if (empty($class)) {
            return $data;
        }

        return new $class(...\array_values($data));
    }

    /** @return static */
    final protected function addToCurrent(string $field, $value)
    {
        $this->initCurrentIfNotYet();
        $this->current[$field] = $value;

        return $this;
    }

    /** @return static */
    final protected function removeFromCurrent(string $field)
    {
        $this->initCurrentIfNotYet();

        unset($this->current[$field]);

        return $this;
    }

    final protected function currentSetup(): array
    {
        $this->initCurrentIfNotYet();

        return $this->current;
    }

    /** @return static */
    final protected function replaceCurrentSetup(array $setup)
    {
        $this->current = $setup;

        return $this;
    }

    final protected function initCurrentIfNotYet(): void
    {
        if (empty($this->current)) {
            $this->current = $this->base;
        }
    }
}
