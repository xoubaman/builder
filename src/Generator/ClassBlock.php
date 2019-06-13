<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Generator;

final class ClassBlock
{
    use TemplateReplacement;

    private const BUILDER_NAMESPACE_PLACEHOLDER = '[BUILDER_NAMESPACE]';
    private const NAMESPACE_PLACEHOLDER         = '[NAMESPACE]';
    private const CLASSNAME_PLACEHOLDER         = '[CLASSNAME]';
    private const BASE_VALUES_PLACEHOLDER       = '[BASE_VALUES]';
    private const SETTERS_PLACEHOLDER           = '[SETTERS]';

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
        [BASE_VALUES]
    }

    public function build(): [CLASSNAME]
    {
        return parent::build();
    }

    public function cloneLast(): [CLASSNAME]
    {
        return parent::cloneLast();
    }

    [SETTERS]
}
";

    public static function generateClass(
        string $namespace,
        string $classname,
        array $constructorArguments
    ): string {
        $baseValues      = BaseArgumentsBlock::generate($constructorArguments);
        $propertySetters = SettersBlock::generate($constructorArguments);

        return self::replaceInTemplate(
            self::TEMPLATE,
            [
                self::NAMESPACE_PLACEHOLDER         => $namespace,
                self::CLASSNAME_PLACEHOLDER         => $classname,
                self::BASE_VALUES_PLACEHOLDER       => $baseValues,
                self::SETTERS_PLACEHOLDER           => $propertySetters,
                self::BUILDER_NAMESPACE_PLACEHOLDER => self::BUILDER_NAMESPACE,
            ]
        );
    }
}
