<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Generator;

final class BaseArgumentsBlock
{
    use TemplateReplacement;

    private const ARGUMENT_DEFAULTS_PLACEHOLDER = '[ARGUMENT_DEFAULTS]';

    private const TEMPLATE = '$this->base = [
[ARGUMENT_DEFAULTS]
        ];';

    public static function generate(array $constructorArguments): string
    {
        $defaults = self::buildDefaultArguments($constructorArguments);

        return self::replaceInTemplate(
            self::TEMPLATE,
            [
                self::ARGUMENT_DEFAULTS_PLACEHOLDER => implode(PHP_EOL, $defaults),
            ]
        );
    }

    /** @param Argument[] $constructorArguments */
    private static function buildDefaultArguments(array $constructorArguments): array
    {
        return array_map(
            self::baseArgumentClosure(),
            $constructorArguments,
            []
        );
    }

    private static function baseArgumentClosure(): \Closure
    {
        return function (Argument $argument): string {
            return sprintf(
                "            '%s' => %s,",
                $argument->name(),
                $argument->default()
            );
        };
    }
}
