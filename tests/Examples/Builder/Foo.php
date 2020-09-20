<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Tests\Examples\Builder;

class Foo
{
    public const DEFAULT_AFTER_BUILD_METHOD_PARAM = 'not called';

    /** @var string */
    private $param1;
    /** @var string */
    private $param2;
    /** @var int */
    private $param3;

    /** @var string */
    public $afterBuildMethodParameter;
    /** @var bool */
    private $byCustomConstructor;

    public function __construct(string $param1, string $param2, int $param3)
    {
        $this->param1 = $param1;
        $this->param2 = $param2;
        $this->param3 = $param3;

        $this->afterBuildMethodParameter = self::DEFAULT_AFTER_BUILD_METHOD_PARAM;

        $this->byCustomConstructor = false;
    }

    public function customConstructor(string $param1, string $param2, int $param3): self
    {
        $instance = new self($param1, $param2, $param3);
        $instance->byCustomConstructor = true;

        return $instance;
    }

    public function afterBuildMethod(string $param): void
    {
        $this->afterBuildMethodParameter = $param;
    }

    public function afterBuildMethodCalleParam(): string
    {
        return $this->afterBuildMethodParameter;
    }

    public function instantiatedByCustomConstructor(): bool
    {
        return $this->byCustomConstructor;
    }
}
