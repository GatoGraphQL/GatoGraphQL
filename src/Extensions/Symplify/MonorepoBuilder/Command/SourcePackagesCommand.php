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
use Symplify\PackageBuilder\Console\ShellCode;

final class SourcePackagesCommand extends AbstractSymplifyCommand
{
    /**
     * @var SourcePackagesProvider
     */
    private $sourcePackagesProvider;

    public function __construct(SourcePackagesProvider $sourcePackagesProvider)
    {
        $this->sourcePackagesProvider = $sourcePackagesProvider;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Provides source packages (i.e. packages with code under src/ and tests/), in json format. Useful for GitHub Actions Workflow');
        $this->addOption(
            Option::JSON,
            null,
            InputOption::VALUE_NONE,
            'Print with encoded JSON format.'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $sourcePackages = $this->sourcePackagesProvider->provideSourcePackages();

        $asJSON = (bool) $input->getOption(Option::JSON);

        // JSON: must be without spaces, otherwise it breaks GitHub Actions json
        $response = $asJSON ? Json::encode($sourcePackages) : implode(' ', $sourcePackages);
        $this->symfonyStyle->writeln($response);

        return ShellCode::SUCCESS;
    }
}
