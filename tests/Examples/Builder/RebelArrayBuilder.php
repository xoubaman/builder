<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Tests\Examples\Builder;

use Xoubaman\Builder\Builder;

final class RebelArrayBuilder extends Builder
{
    public const DEFAULT_NAME    = 'Han Solo';
    public const DEFAULT_ADDRESS = 'Tatooine';
    public const DEFAULT_SHIP    = 'Millennium Falcon';

    protected $base = [
        'here'    => self::DEFAULT_NAME,
        'address' => self::DEFAULT_ADDRESS,
        'ship'    => self::DEFAULT_SHIP,
    ];

    public function build(): array
    {
        return parent::build();
    }

    public function cloneLast(): array
    {
        return parent::cloneLast();
    }
}
