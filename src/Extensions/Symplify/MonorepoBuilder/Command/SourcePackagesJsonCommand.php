<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Command;

use Nette\Utils\Json;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Json\SourcePackagesJsonProvider;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use Symplify\PackageBuilder\Console\ShellCode;

final class SourcePackagesJsonCommand extends AbstractSymplifyCommand
{
    /**
     * @var SourcePackagesJsonProvider
     */
    private $sourcePackagesJsonProvider;

    public function __construct(SourcePackagesJsonProvider $sourcePackagesJsonProvider)
    {
        $this->sourcePackagesJsonProvider = $sourcePackagesJsonProvider;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Provides source packages (i.e. packages with code under src/ and tests/), in json format. Useful for GitHub Actions Workflow');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $sourcePackageEntries = $this->sourcePackagesJsonProvider->provideSourcePackages();

        // must be without spaces, otherwise it breaks GitHub Actions json
        $json = Json::encode($sourcePackageEntries);
        $this->symfonyStyle->writeln($json);

        return ShellCode::SUCCESS;
    }
}
