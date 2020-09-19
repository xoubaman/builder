<?php
declare(strict_types=1);

namespace Xoubaman\Builder;

abstract class Builder
{
    protected const CLASS_TO_BUILD = '';

    /** @var array<mixed> */
    protected $lastBuilt = [];
    /** @var array<mixed> */
    protected $current   = [];
    /** @var array<mixed> */
    protected $base      = [];

    /** @return mixed */
    public function build()
    {
        $this->initCurrentIfNotYet();
        $instance        = $this->newInstanceWithParameters($this->current);
        $this->lastBuilt = $this->current;
        $this->current   = [];

        return $instance;
    }

    /** @return mixed */
    public function cloneLast()
    {
        return $this->newInstanceWithParameters($this->lastBuilt);
    }

    /**
     * @param array<mixed> $data
     * @return mixed
     */
    private function newInstanceWithParameters(array $data)
    {
        $class = static::CLASS_TO_BUILD;

        if (empty($class)) {
            return $data;
        }

        return new $class(...\array_values($data));
    }

    /**
     * @param mixed $value
     * @return static
     */
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

    /** @return array<mixed> */
    final protected function currentSetup(): array
    {
        $this->initCurrentIfNotYet();

        return $this->current;
    }

    /**
     * @param array<mixed> $setup
     * @return static
     */
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
