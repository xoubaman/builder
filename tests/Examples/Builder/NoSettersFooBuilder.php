<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Tests\Examples\Builder;

use Xoubaman\Builder\Builder;

/**
 * @method withParam1(string $param1)
 * @method withParam2(string $param2)
 * @method withParam3(int $param3)
 */
final class NoSettersFooBuilder extends Builder
{
    public const PARAM_1_VALUE = 'a value';
    public const PARAM_2_VALUE = 'some other value';
    public const PARAM_3_VALUE = 8;

    private const PARAM_1 = 'param1';
    private const PARAM_2 = 'param2';
    private const PARAM_3 = 'param3';

    protected const CLASS_TO_BUILD = Foo::class;

    public function __construct()
    {
        $this->base = [
            self::PARAM_1 => self::PARAM_1_VALUE,
            self::PARAM_2 => self::PARAM_2_VALUE,
            self::PARAM_3 => self::PARAM_3_VALUE,
        ];
    }

    public function build(): Foo
    {
        return parent::build();
    }
}
