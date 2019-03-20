<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Tests;

class Rebel
{
    /** @var string */
    private $name;
    /** @var string */
    private $address;
    /** @var string */
    private $ship;

    public function __construct(string $name, string $address, string $ship)
    {
        $this->name    = $name;
        $this->address = $address;
        $this->ship    = $ship;
    }
}
