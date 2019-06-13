<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Tests\Examples\Generator;

use Xoubaman\Builder\Builder;

final class SampleClassBuilder extends Builder
{
    protected const CLASS_TO_BUILD = SampleClass::class;

    public function __construct()
    {
        $this->base = [
            'propertyOne' => 'some string',
            'propertyTwo' => true,
        ];
    }

    public function build(): SampleClass
    {
        return parent::build();
    }

    public function cloneLast(): SampleClass
    {
        return parent::cloneLast();
    }

    public function withPropertyOne(string $propertyOne): self
    {
        $this->addToCurrent('propertyOne', $propertyOne);

        return $this;
    }

    public function withPropertyTwo(bool $propertyTwo): self
    {
        $this->addToCurrent('propertyTwo', $propertyTwo);

        return $this;
    }
}
