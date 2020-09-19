<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Tests\Examples\Builder;

class Rebel
{
    /** @var string */
    private $name;
    /** @var string */
    private $address;
    /** @var string */
    private $ship;
    /** @var bool */
    public $isTemptedByTheDarkSide;

    public function __construct(string $name, string $address, string $ship)
    {
        $this->name    = $name;
        $this->address = $address;
        $this->ship    = $ship;

        $this->isTemptedByTheDarkSide = true;
    }

    public function setTemptationByDarkSide(bool $isTempted): void
    {
        $this->isTemptedByTheDarkSide = $isTempted;
    }

    public function isTemptedByDarkSide(): bool
    {
        return $this->isTemptedByTheDarkSide;
    }
}
