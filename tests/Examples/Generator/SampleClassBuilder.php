<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Tests\Examples\Generator;

use Xoubaman\Builder\Builder;

final class SampleClassBuilder extends Builder
{
    protected const CLASS_TO_BUILD = SampleClass::class;

    private const PROPERTY_ONE = 'propertyOne';
    private const PROPERTY_TWO = 'propertyTwo';

    public function __construct()
    {
        $this->base = [
            self::PROPERTY_ONE => 'some string',
            self::PROPERTY_TWO => true,
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
        return $this->addToCurrent(self::PROPERTY_ONE, $propertyOne);
    }

    public function withPropertyTwo(bool $propertyTwo): self
    {
        return $this->addToCurrent(self::PROPERTY_TWO, $propertyTwo);
    }
}
