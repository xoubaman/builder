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
     * Method to call for instantiation, leave empty to use regular constructor
     */
    protected const USE_CONSTRUCTOR = '';

    /**
     * Array holding a method name to be called after instantiation
     * First element will be used as the method name, subsequent elements as
     * parameters when calling it
     */
    protected const AFTER_BUILD_CALL = [];

    /** @var array<mixed> */
    protected array $lastSetup = [];
    /** @var array<mixed> */
    protected array $current = [];
    /** @var array<mixed> */
    protected array $base = [];

    public function build(): mixed
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

    public function cloneLast(): mixed
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
     */
    private function newInstanceWithSetup(array $setup): mixed
    {
        $setupToParam = array_map(
            static fn($param) => is_callable($param) ? $param() : $param,
            $setup
        );

        if (empty(static::CLASS_TO_BUILD)) {
            return $setupToParam;
        }

        $class = static::CLASS_TO_BUILD;

        if (empty(static::USE_CONSTRUCTOR)) {
            return new $class(...array_values($setupToParam));
        }

        return call_user_func_array([$class, static::USE_CONSTRUCTOR], array_values($setupToParam));
    }

    final protected function addToCurrent(string $field, mixed $value): static
    {
        $this->initCurrentIfNotYet();
        $this->current[$field] = $value;

        return $this;
    }

    final protected function removeFromCurrent(string $field): static
    {
        $this->initCurrentIfNotYet();

        unset($this->current[$field]);

        return $this;
    }

    /**
     * @return array<mixed>
     */
    final protected function currentSetup(): array
    {
        $this->initCurrentIfNotYet();

        return $this->current;
    }

    /**
     * @param array<mixed> $setup
     */
    final protected function replaceCurrentSetup(array $setup): static
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
     * @param array<mixed> $callbackParameters
     */
    private function doCallAfterBuild(object $instance, array $callbackParameters): object
    {
        $callable = [$instance, array_shift($callbackParameters)];
        call_user_func_array($callable, $callbackParameters);

        return $instance;
    }

    /**
     * @param array<mixed> $arguments
     */
    public function __call(string $name, array $arguments): self
    {
        $this->initCurrentIfNotYet();
        $methodWithoutPrefix = mb_substr($name, mb_strlen('with'));
        $paramName          = lcfirst($methodWithoutPrefix);

        if (!array_key_exists($paramName, $this->current)) {
            throw new \OutOfBoundsException(sprintf('There is no key %s defined in current build setup', $paramName));
        }

        $this->addToCurrent($paramName, $arguments[0]);

        return $this;
    }
}
