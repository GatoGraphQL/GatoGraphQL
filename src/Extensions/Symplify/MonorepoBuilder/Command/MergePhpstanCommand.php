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
    private PHPStanNeonContentProvider $phpstanNeonContentProvider;
    private NeonFilePrinter $neonFilePrinter;

    public function __construct(
        PHPStanNeonContentProvider $phpstanNeonContentProvider,
        NeonFilePrinter $neonFilePrinter
    ) {
        $this->phpstanNeonContentProvider = $phpstanNeonContentProvider;
        $this->neonFilePrinter = $neonFilePrinter;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Create the phpstan.neon.dist config for the monorepo, including all phpstan.neon.dist files from all packages');
        $this->addOption(
            Option::OUTPUT_FILE,
            null,
            InputOption::VALUE_REQUIRED,
            'Path to dump root phpstan.neon.dist to',
            getcwd() . DIRECTORY_SEPARATOR . 'phpstan.neon.dist'
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
        $skipUnmigrated = (bool) $input->getOption(Option::SKIP_UNMIGRATED);
        $neonFileContent = $this->phpstanNeonContentProvider->provideContent($skipUnmigrated);

        $outputFilePath = (string) $input->getOption(Option::OUTPUT_FILE);
        $this->neonFilePrinter->printContentToOutputFile($neonFileContent, $outputFilePath);

        return ShellCode::SUCCESS;
    }
}
