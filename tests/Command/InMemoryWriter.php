<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Tests\Command;

use RuntimeException;
use Xoubaman\Builder\Command\FileAlreadyExists;
use Xoubaman\Builder\Command\Writer;

final class InMemoryWriter implements Writer
{
    /** @var array<string> */
    private $writes = [];

    public function writeIn(string $path, string $content): void
    {
        if (array_key_exists($path, $this->writes)) {
            throw FileAlreadyExists::inPath($path);
        }

        $this->writes[$path] = $content;
    }

    public function contentWrittenInPath(string $path): string
    {
        if (!array_key_exists($path, $this->writes)) {
            throw new RuntimeException(sprintf('Nothing was written in path %s', $path));
        }

        return $this->writes[$path];
    }
}
