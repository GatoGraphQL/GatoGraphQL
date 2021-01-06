<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Command;

use Nette\Utils\Json;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Json\PackageCodePathsJsonProvider;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use Symplify\PackageBuilder\Console\ShellCode;

final class PackageCodePathsJsonCommand extends AbstractSymplifyCommand
{
    /**
     * @var PackageCodePathsJsonProvider
     */
    private $packageCodePathsJsonProvider;

    public function __construct(PackageCodePathsJsonProvider $packageCodePathsJsonProvider)
    {
        $this->packageCodePathsJsonProvider = $packageCodePathsJsonProvider;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Provides package code paths in json format. Useful for GitHub Actions Workflow');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $packageCodePaths = $this->packageCodePathsJsonProvider->providePackageCodePaths();

        // must be without spaces, otherwise it breaks GitHub Actions json
        $json = Json::encode($packageCodePaths);
        $this->symfonyStyle->writeln($json);

        return ShellCode::SUCCESS;
    }
}
