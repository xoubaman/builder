<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Command;

interface Writer
{
    /** @throws FileAlreadyExists */
    public function writeIn(string $path, string $content): void;
}
