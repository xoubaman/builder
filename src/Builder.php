<?php
declare(strict_types=1);

namespace Xoubaman\Builder;

use function array_values;

abstract class Builder
{
    /**
     * FQN of the class to build, leave empty to build an array
     */
    protected const CLASS_TO_BUILD = '';

    /**
     * Array holding a method name to be called after instantiation
     * First element will be used as the method name, subsequent elements as
     * parameters when calling it
     */
    protected const AFTER_BUILD_CALL = [];

    /** @var array<mixed> */
    protected $lastSetup = [];
    /** @var array<mixed> */
    protected $current = [];
    /** @var array<mixed> */
    protected $base = [];

    /** @return mixed */
    public function build()
    {
        $this->initCurrentIfNotYet();
        $instance        = $this->newInstanceWithSetup($this->current);
        $this->lastSetup = $this->current;
        $this->current   = [];

        if (!empty(static::AFTER_BUILD_CALL) && is_object($instance)) {
            $instance = $this->doCallAfterBuild($instance, static::AFTER_BUILD_CALL);
        }

        return $instance;
    }

    /** @return mixed */
    public function cloneLast()
    {
        return $this->newInstanceWithSetup($this->lastSetup);
    }

    public function repeatLastSetup(): self
    {
        $this->current = $this->lastSetup;

        return $this;
    }

    /**
     * @param array<mixed> $setup
     * @return mixed
     */
    private function newInstanceWithSetup(array $setup)
    {
        $class = static::CLASS_TO_BUILD;

        $setupToParam = array_map(
            function ($param) {
                if (is_callable($param)) {
                    return $param();
                }

                return $param;
            },
            $setup
        );

        if (empty($class)) {
            return $setupToParam;
        }

        return new $class(...array_values($setupToParam));
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

    /**
     * @param object $instance
     * @param array<mixed> $callbackParameters
     * @return object
     */
    private function doCallAfterBuild(object $instance, array $callbackParameters): object
    {
        $callable = [$instance, array_shift($callbackParameters)];
        call_user_func_array($callable, $callbackParameters);

        return $instance;
    }
}
