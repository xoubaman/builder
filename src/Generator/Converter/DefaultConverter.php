<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Generator\Converter;

use Xoubaman\Builder\Generator\ClassMetadata\ClassMetadata;
use Xoubaman\Builder\Generator\Converter;
use Xoubaman\Builder\Generator\TemplateReplacement;

final class DefaultConverter implements Converter
{
    use TemplateReplacement;

    private const TEMPLATE = <<<TPL
<?php
declare(strict_types=1);

namespace [NAMESPACE];

use Xoubaman\Builder\Builder;

final class [CLASSNAME]Builder extends Builder
{
    protected const CLASS_TO_BUILD = [CLASSNAME]::class;

    [BASE_ARRAY_KEYS]

    public function __construct()
    {
        \$this->base = [
            [BASE_ARRAY]
        ];
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

TPL;

    public function __invoke(ClassMetadata $classMetadata): string
    {
        $arguments     = $classMetadata->arguments();
        $baseArrayKeys = $arguments->mapWith(new ArgumentToConstantDeclaration());
        $baseArray     = $arguments->mapWith(new ArgumentToBaseInitialization());
        $setters       = $arguments->mapWith(new ArgumentToSetter());

        return $this->replaceInTemplate(
            self::TEMPLATE,
            [
                '[NAMESPACE]'       => $classMetadata->namespace(),
                '[CLASSNAME]'       => $classMetadata->className(),
                '[BASE_ARRAY_KEYS]' => $this->toIndentedCode($baseArrayKeys, 1),
                '[BASE_ARRAY]'      => $this->toIndentedCode($baseArray, 3),
                '[SETTERS]'         => $this->toIndentedCode($setters, 1),
            ]
        );
    }

    /** @param array<string> $array */
    private function toIndentedCode(array $array, int $indentantionLevels): string
    {
        $indentation = str_repeat('    ', $indentantionLevels);

        return trim(implode(PHP_EOL.$indentation, $array));
    }
}
