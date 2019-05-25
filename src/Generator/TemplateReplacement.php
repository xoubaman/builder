<?php

namespace Xoubaman\Builder\Generator;

trait TemplateReplacement
{
    private static function replaceInTemplate(string $template, array $replacements): string
    {
        $placeholders = array_keys($replacements);
        $values       = array_values($replacements);

        return str_replace($placeholders, $values, $template);
    }
}
