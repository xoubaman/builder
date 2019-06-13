<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Generator;

final class SettersBlock
{
    use TemplateReplacement;

    private const PROPERTY_NAME_PLACEHOLDER = '[PROPERTY_NAME]';
    private const PROPERTY_TYPE_PLACEHOLDER = '[PROPERTY_TYPE]';
    private const PROPERTY_VAR_PLACEHOLDER  = '[PROPERTY_VAR]';

    private const TEMPLATE = '
    public function with[PROPERTY_NAME]([PROPERTY_TYPE] $[PROPERTY_VAR]): self
    {
        $this->addToCurrent(\'[PROPERTY_VAR]\', $[PROPERTY_VAR]);

        return $this;
    }';

    /** @param Argument[] $constructorArguments */
    public static function generate(array $constructorArguments): string
    {
        $setters = array_map(
            self::singleArgumentSetterClosure(),
            $constructorArguments,
            []
        );

        return trim(implode(PHP_EOL, $setters));
    }

    private static function singleArgumentSetterClosure(): \Closure
    {
        return function (Argument $argument) {
            return self::replaceInTemplate(
                self::TEMPLATE,
                [
                    self::PROPERTY_NAME_PLACEHOLDER => ucfirst($argument->name()),
                    self::PROPERTY_VAR_PLACEHOLDER  => $argument->name(),
                    self::PROPERTY_TYPE_PLACEHOLDER => $argument->type(),
                ]
            );
        };
    }
}
