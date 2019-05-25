<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Tests\Examples\Generator;

use Xoubaman\Builder\Builder;

final class PocoClassBuilder extends Builder
{
    protected const CLASS_TO_BUILD = PocoClass::class;

    public function __construct()
    {
        $this->base = [
            'propertyOne' => 'some string',
            'propertyTwo' => true,
        ];
    }

    public function build(): PocoClass
    {
        return parent::build();
    }

    public function cloneLast(): PocoClass
    {
        return parent::cloneLast();
    }

    public function withPropertyOne(string $propertyOne): self
    {
        $this->addToCurrent('property_one', $propertyOne);

        return $this;
    }

    public function withPropertyTwo(bool $propertyTwo): self
    {
        $this->addToCurrent('property_two', $propertyTwo);

        return $this;
    }
}
