<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Command;

class FileAlreadyExists extends \RuntimeException
{
    public static function inPath(string $path): self
    {
        return new self(sprintf('File %s already exists', $path));
    }
}
