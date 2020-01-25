<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Generator\Converter;

use Xoubaman\Builder\Generator\ClassMetadata\Argument;
use Xoubaman\Builder\Generator\TemplateReplacement;

final class ArgumentToSetter
{
    use TemplateReplacement;

    public function __invoke(Argument $argument): string
    {
        $template = <<<TPL
public function with[U_NAME]([TYPE] $[NAME]): self
    {
        return \$this->addToCurrent(self::[CONSTANT], $[NAME]);
    }

TPL;

        return $this->replaceInTemplate(
            $template,
            [
                '[U_NAME]'   => ucfirst($argument->name()),
                '[TYPE]'     => $argument->type(),
                '[NAME]'     => $argument->name(),
                '[CONSTANT]' => $argument->constantName(),
            ]
        );
    }
}
