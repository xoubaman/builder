<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Tests;

class RebelBuilder
{
    const DEFAULT_NAME    = 'Han Solo';
    const DEFAULT_ADDRESS = 'Tatooine';
    const DEFAULT_SHIP    = 'Millennium Falcon';

    /** @var array */
    private $base = [
        'name'    => self::DEFAULT_NAME,
        'address' => self::DEFAULT_ADDRESS,
        'ship'    => self::DEFAULT_SHIP,
    ];
    /** @var array */
    private $current = [];
    /** @var array */
    private $lastBuilt = [];

    public function build(): Rebel
    {
        $this->current = $this->current + $this->base;

        $instance = $this->buildWith($this->current);

        $this->lastBuilt = $this->current;
        $this->current   = [];

        return $instance;
    }

    private function buildWith(array $data): Rebel
    {
        return new Rebel(
            $data['name'],
            $data['address'],
            $data['ship']
        );
    }

    public function cloneLast(): Rebel
    {
        return $this->buildWith($this->lastBuilt);
    }

    private function addToCurrent(string $field, $value): void
    {
        if (empty($this->current)) {
            $this->current = $this->base;
        }

        $this->current[$field] = $value;
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
