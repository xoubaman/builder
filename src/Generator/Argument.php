<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Generator;

final class Argument
{
    private const DEFAULTS = [
        'string'   => "'some string'",
        'bool'     => 'true',
        'int'      => '0',
        'float'    => '0.0',
        'null'     => 'null',
        'array'    => '[]',
        'callable' => 'function(){}',
    ];

    /** @var string */
    private $name;
    /** @var string */
    private $type;
    /** @var string */
    private $default;

    public function __construct(string $name, string $type)
    {
        $this->name    = $name;
        $this->type    = $type;
        $this->default = self::DEFAULTS[$type] ?? 'null';
    }

    public function name(): string
    {
        return $this->name;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function default(): string
    {
        return $this->default;
    }

    public function nameInScreamingSnakeCase(): string
    {
        $snakeCase = (string)preg_replace('/(?<!^)[A-Z]/', '_$0', $this->name);

        return strtoupper($snakeCase);
    }
}
