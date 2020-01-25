<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Tests\Examples\Builder;

use Xoubaman\Builder\Builder;

final class RebelArrayBuilder extends Builder
{
    private const SHIP = 'ship';

    protected $base = [
        'here'     => 'Han Solo',
        'address'  => 'Tatooine',
        self::SHIP => 'Millennium Falcon',
    ];

    public function build(): array
    {
        return parent::build();
    }

    public function cloneLast(): array
    {
        return parent::cloneLast();
    }

    public function withoutShip(): self
    {
        return $this->removeFromCurrent(self::SHIP);
    }
}
