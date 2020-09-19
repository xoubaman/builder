<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Tests\Examples\Builder;

use Xoubaman\Builder\Builder;

final class RebelBuilderWithCallback extends Builder
{
    public const DEFAULT_NAME    = 'Han Solo';
    public const DEFAULT_ADDRESS = 'Tatooine';
    public const DEFAULT_SHIP    = 'Millennium Falcon';

    private const NAME    = 'name';
    private const ADDRESS = 'address';
    private const SHIP    = 'ship';

    protected const CLASS_TO_BUILD   = Rebel::class;
    protected const AFTER_BUILD_CALL = ['setTemptationByDarkSide', false];

    public function __construct()
    {
        $this->base = [
            self::NAME    => self::DEFAULT_NAME,
            self::ADDRESS => self::DEFAULT_ADDRESS,
            self::SHIP    => self::DEFAULT_SHIP,
        ];
    }

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
        return $this->addToCurrent('name', $name);
    }

    public function withAddress(string $address): self
    {
        return $this->addToCurrent('address', $address);
    }

    public function withShip(string $ship): self
    {
        return $this->addToCurrent('ship', $ship);
    }
}
