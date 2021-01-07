<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Command;

use Nette\Utils\Json;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Json\PackageCodePathsProvider;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\ValueObject\Option;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use Symplify\PackageBuilder\Console\ShellCode;

final class PackageCodePathsCommand extends AbstractSymplifyCommand
{
    private PackageCodePathsProvider $packageCodePathsProvider;

    public function __construct(PackageCodePathsProvider $packageCodePathsProvider)
    {
        $this->packageCodePathsProvider = $packageCodePathsProvider;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Provides package code paths in json format. Useful for GitHub Actions Workflow');
        $this->addOption(
            Option::JSON,
            null,
            InputOption::VALUE_NONE,
            'Print with encoded JSON format.'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $asJSON = (bool) $input->getOption(Option::JSON);

        $packageCodePaths = $this->packageCodePathsProvider->providePackageCodePaths();

        // must be without spaces, otherwise it breaks GitHub Actions json
        $response = $asJSON ? Json::encode($packageCodePaths) : implode(' ', $packageCodePaths);
        $this->symfonyStyle->writeln($response);

        return ShellCode::SUCCESS;
    }
}
