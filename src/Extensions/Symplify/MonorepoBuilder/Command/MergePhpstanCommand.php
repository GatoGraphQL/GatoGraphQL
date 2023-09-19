<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Command;

use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Neon\NeonFilePrinter;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\PHPStanNeonContentProvider;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\ValueObject\Option;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use Symplify\PackageBuilder\Console\Command\CommandNaming;
use Symplify\PackageBuilder\Parameter\ParameterProvider;

final class MergePhpstanCommand extends AbstractSymplifyCommand
{
    protected string|int $level;
    
    public function __construct(
        private PHPStanNeonContentProvider $phpstanNeonContentProvider,
        private NeonFilePrinter $neonFilePrinter,
        ParameterProvider $parameterProvider,
    ) {
        parent::__construct();
        $this->level = $parameterProvider->provideParameter(Option::LEVEL);
    }

    protected function configure(): void
    {
        $this->setName(CommandNaming::classToName(self::class));
        $this->setDescription('Create the PHPStan config for the monorepo, including all PHPStan config files from all packages');
        $this->addOption(
            Option::OUTPUT_FILE,
            null,
            InputOption::VALUE_REQUIRED,
            'Path to dump monorepo PHPStan config file',
            getcwd() . DIRECTORY_SEPARATOR . 'phpstan.neon'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $packagesToSkip = [];

        $neonFileContent = $this->phpstanNeonContentProvider->provideContent($packagesToSkip, $this->level);

        $outputFilePath = (string) $input->getOption(Option::OUTPUT_FILE);
        $this->neonFilePrinter->printContentToOutputFile($neonFileContent, $outputFilePath);

        return self::SUCCESS;
    }
}
