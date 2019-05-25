<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Generator;

final class ClassBlock
{
    private const NAMESPACE_PLACEHOLDER         = '[NAMESPACE]';
    private const CLASSNAME_PLACEHOLDER         = '[CLASSNAME]';
    private const BUILDER_NAMESPACE_PLACEHOLDER = '[BUILDER_NAMESPACE]';

    private const BUILDER_NAMESPACE = 'Xoubaman\Builder\Builder';

    private const TEMPLATE = "".
                             "<?php
declare(strict_types=1);

namespace [NAMESPACE];

use [BUILDER_NAMESPACE];

final class [CLASSNAME]Builder extends Builder
{
    protected const CLASS_TO_BUILD = [CLASSNAME]::class;

    public function __construct()
    {
    }

    public function build(): [CLASSNAME]
    {
        return parent::build();
    }

    public function cloneLast(): [CLASSNAME]
    {
        return parent::cloneLast();
    }
}
";

    public static function generateClass(string $namespace, string $classname): string
    {
        return self::replaceInTemplate(
            [
                self::NAMESPACE_PLACEHOLDER         => $namespace,
                self::CLASSNAME_PLACEHOLDER         => $classname,
                self::BUILDER_NAMESPACE_PLACEHOLDER => self::BUILDER_NAMESPACE,
            ]
        );
    }

    private static function replaceInTemplate(array $replacements): string
    {
        $placeholders = array_keys($replacements);
        $values       = array_values($replacements);

        return str_replace($placeholders, $values, self::TEMPLATE);
    }

}
