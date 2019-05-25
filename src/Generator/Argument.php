<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Generator;

final class Argument
{

    private const DEFAULTS = [
        'string' => "'some string'",
        'bool'   => 'true',
        'int'    => '0',
        'float'  => '0.0',
        'null'   => 'null',
        'array'  => '[]',
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

    public function default(): string
    {
        return $this->default;
    }
}
