<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Tests\Examples\Builder;

use Xoubaman\Builder\Builder;

final class RebelArrayBuilder extends Builder
{
    private const SHIP = 'ship';

    protected $base = [
        'name'     => 'Han Solo',
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

    public function withNestedAddress(string $nestedAddress): self
    {
        $setup = $this->currentSetup();
        $currentAddress = $setup['address'];
        $newAddress = [$currentAddress => $nestedAddress];
        $setup['address'] = $newAddress;

        return $this->replaceCurrentSetup($setup);
    }
}
