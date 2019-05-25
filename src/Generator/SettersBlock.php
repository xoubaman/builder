<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Generator;

final class SettersBlock
{
    public static function generate(array $constructorArguments): string
    {
        return 'public function withPropertyOne(string $propertyOne): self
    {
        $this->addToCurrent(\'property_one\', $propertyOne);

        return $this;
    }

    public function withPropertyTwo(bool $propertyTwo): self
    {
        $this->addToCurrent(\'property_two\', $propertyTwo);

        return $this;
    }';
    }
}
