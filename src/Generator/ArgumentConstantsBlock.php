<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Generator;

final class ArgumentConstantsBlock
{
    use TemplateReplacement;

    private const CONSTANT_PLACEHOLDER = '[CONSTANT]';
    private const VALUE_PLACEHOLDER    = '[VALUE]';

    private const TEMPLATE = ''.
                             "    private const [CONSTANT] = '[VALUE]';";

    public static function generate(array $constructorArguments): string
    {
        $constants = array_map(
            self::singleConstantClosure(),
            $constructorArguments,
            []
        );

        return trim(implode(PHP_EOL, $constants));
    }

    private static function singleConstantClosure(): \Closure
    {
        return function (Argument $argument): string {
            return self::replaceInTemplate(
                self::TEMPLATE,
                [
                    self::CONSTANT_PLACEHOLDER => $argument->nameInScreamingSnakeCase(),
                    self::VALUE_PLACEHOLDER    => $argument->name(),
                ]
            );
        };
    }
}
