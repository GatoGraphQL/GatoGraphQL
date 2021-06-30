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
use Symplify\PackageBuilder\Console\ShellCode;

final class MergePhpstanCommand extends AbstractSymplifyCommand
{
    public function __construct(
        private PHPStanNeonContentProvider $phpstanNeonContentProvider,
        private NeonFilePrinter $neonFilePrinter
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Create the PHPStan config for the monorepo, including all PHPStan config files from all packages');
        $this->addOption(
            Option::OUTPUT_FILE,
            null,
            InputOption::VALUE_REQUIRED,
            'Path to dump monorepo PHPStan config file',
            getcwd() . DIRECTORY_SEPARATOR . 'phpstan.neon'
        );
        $this->addOption(
            Option::SKIP_UNMIGRATED,
            null,
            InputOption::VALUE_NONE,
            'Skip the not-yet-migrated to PSR-4 packages.'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // If --skip-unmigrated, fetch the list of failing unmigrated packages
        $skipUnmigrated = (bool) $input->getOption(Option::SKIP_UNMIGRATED);
        $unmigratedFailingPackages = $input->getOption(Option::UNMIGRATED_FAILING_PACKAGES);
        $packagesToSkip = $skipUnmigrated ? $unmigratedFailingPackages : [];

        $neonFileContent = $this->phpstanNeonContentProvider->provideContent($packagesToSkip);

        $outputFilePath = (string) $input->getOption(Option::OUTPUT_FILE);
        $this->neonFilePrinter->printContentToOutputFile($neonFileContent, $outputFilePath);

        return ShellCode::SUCCESS;
    }
}
