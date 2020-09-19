<?php
declare(strict_types=1);

namespace Xoubaman\Builder\Command;

use InvalidArgumentException;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Xoubaman\Builder\Generator\BuilderGenerator;
use Xoubaman\Builder\Generator\Converter;
use Xoubaman\Builder\Generator\Converter\DefaultConverter;

class GenerateBuilderCommand extends Command
{
    public const NAME = 'build';

    private const ARG_CLASS = 'class';
    private const ARG_CONVERTER = 'converter';

    /** @var BuilderGenerator */
    private $generator;
    /** @var Writer */
    private $writer;

    public function __construct(BuilderGenerator $generator, Writer $writer)
    {
        parent::__construct();

        $this->generator = $generator;
        $this->writer = $writer;
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Creates a builder for a given class')
            ->setDefinition(
                [
                    new InputArgument(
                        self::ARG_CLASS,
                        InputArgument::REQUIRED,
                        'Class FQN to generate the builder from'
                    ),
                    new InputArgument(
                        self::ARG_CONVERTER,
                        InputArgument::OPTIONAL,
                        'A converter FQN to generate the builder'
                    ),
                ]
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $class           = $this->getClassFromInput($input);
        $converter       = $this->getConverter($input);
        $builderContent  = $this->generator->forClassWithConverter($class, $converter);
        $destinationPath = $this->getPathFromOriginalClass($class);
        $this->writer->writeIn($destinationPath, $builderContent);
        $output->writeln(sprintf("Builder class created in '%s'", $destinationPath));

        return 1;
    }

    private function getClassFromInput(InputInterface $input): string
    {
        $class = $input->getArgument(self::ARG_CLASS);

        if (!is_string($class)) {
            throw new InvalidArgumentException('Cannot read classname from input');
        }

        return $class;
    }

    private function getPathFromOriginalClass(string $class): string
    {
        $reflector        = ReflectionClass::createFromName($class);
        $file             = (string)$reflector->getFileName();
        $dir              = dirname($file);
        $builderClassname = $reflector->getShortName() . 'Builder.php';

        return $dir . DIRECTORY_SEPARATOR . $builderClassname;
    }

    private function getConverter(InputInterface $input): Converter
    {
        $converterClass = $input->getArgument(self::ARG_CONVERTER) ?? DefaultConverter::class;

        $converter = new $converterClass;

        if (!$converter instanceof Converter) {
            throw new InvalidArgumentException('Converter parameter is not a Converter implementation');
        }

        return $converter;
    }
}
