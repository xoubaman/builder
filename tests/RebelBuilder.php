<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Tests;

use Xoubaman\Builder\Builder;

final class RebelBuilder extends Builder
{
    const DEFAULT_NAME    = 'Han Solo';
    const DEFAULT_ADDRESS = 'Tatooine';
    const DEFAULT_SHIP    = 'Millennium Falcon';

    protected const CLASS_TO_BUILD = Rebel::class;

    protected $base = [
        'name'    => self::DEFAULT_NAME,
        'address' => self::DEFAULT_ADDRESS,
        'ship'    => self::DEFAULT_SHIP,
    ];

    public function build(): Rebel
    {
        return parent::build();
    }

    public function cloneLast(): Rebel
    {
        return parent::cloneLast();
    }

    public function withName(string $name): self
    {
        $this->addToCurrent('name', $name);

        return $this;
    }

    public function withAddress(string $address): self
    {
        $this->addToCurrent('address', $address);

        return $this;
    }

    public function withShip(string $ship): self
    {
        $this->addToCurrent('ship', $ship);

        return $this;
    }
}
