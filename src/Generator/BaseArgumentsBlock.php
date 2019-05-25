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

    private static function buildDefaultArguments(array $constructorArguments): array
    {
        $defaults = [];
        /** @var Argument $argument */
        foreach ($constructorArguments as $argument) {
            $defaults[] = sprintf(
                "            '%s' => %s,",
                $argument->name(),
                $argument->default()
            );
        }

        return $defaults;
    }
}
