<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Command;

use Nette\Utils\Json;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Json\PackageEntriesJsonProvider;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\ValueObject\Option;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use Symplify\PackageBuilder\Console\Command\CommandNaming;
use Symplify\PackageBuilder\Console\ShellCode;

final class PackageEntriesJsonCommand extends AbstractSymplifyCommand
{
    public function __construct(private PackageEntriesJsonProvider $packageEntriesJsonProvider)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(CommandNaming::classToName(self::class));
        $this->setDescription('Provides package entries in json format. Useful for GitHub Actions Workflow');
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
        /** @var string[] $fileFilter */
        $fileFilter = $input->getOption(Option::FILTER);

        $packageEntries = $this->packageEntriesJsonProvider->providePackageEntries($fileFilter);

        // must be without spaces, otherwise it breaks GitHub Actions json
        $json = Json::encode($packageEntries);
        $this->symfonyStyle->writeln($json);

        return self::SUCCESS;
    }
}
