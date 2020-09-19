<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Command;

use SplFileObject;

final class FileSystemWriter implements Writer
{
    public function writeIn(string $path, string $content): void
    {
        if (file_exists($path)) {
            throw FileAlreadyExists::inPath($path);
        }

        $file = new SplFileObject($path, 'w');

        $file->fwrite($content);
    }
}
