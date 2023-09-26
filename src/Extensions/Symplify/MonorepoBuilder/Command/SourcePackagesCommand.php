<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Command;

use Nette\Utils\Json;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Json\SourcePackagesProvider;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\ValueObject\Option;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use Symplify\PackageBuilder\Console\Command\CommandNaming;

final class SourcePackagesCommand extends AbstractSymplifyCommand
{
    public function __construct(
        private SourcePackagesProvider $sourcePackagesProvider,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(CommandNaming::classToName(self::class));
        $this->setDescription('Provides source packages (i.e. packages with code under src/ and tests/), in json format. Useful for GitHub Actions Workflow');
        $this->addOption(
            Option::JSON,
            null,
            InputOption::VALUE_NONE,
            'Print with encoded JSON format.'
        );
        $this->addOption(
            Option::PSR4_ONLY,
            null,
            InputOption::VALUE_NONE,
            'Skip the non-PSR-4 packages.'
        );
        $this->addOption(
            Option::SUBFOLDER,
            null,
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            'Add paths to a subfolder from the package.',
            []
        );
        $this->addOption(
            Option::FILTER,
            null,
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            'Filter the packages to those from the list of files. Useful to split monorepo on modified packages only',
            []
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $asJSON = (bool) $input->getOption(Option::JSON);

        $packagesToSkip = [];

        /** @var string[] $subfolders */
        $subfolders = $input->getOption(Option::SUBFOLDER);
        /** @var string[] $fileFilter */
        $fileFilter = $input->getOption(Option::FILTER);

        $sourcePackages = $this->sourcePackagesProvider->provideSourcePackages($packagesToSkip, $fileFilter);

        // Point to some subfolder?
        if ($subfolders !== []) {
            $sourcePackagePaths = [];
            foreach ($sourcePackages as $sourcePackage) {
                foreach ($subfolders as $subfolder) {
                    $sourcePackagePaths[] = $sourcePackage . DIRECTORY_SEPARATOR . $subfolder;
                }
            }
        } else {
            $sourcePackagePaths = $sourcePackages;
        }

        // JSON: must be without spaces, otherwise it breaks GitHub Actions json
        $response = $asJSON ? Json::encode($sourcePackagePaths) : implode(' ', $sourcePackagePaths);
        $this->symfonyStyle->writeln($response);

        return self::SUCCESS;
    }
}
