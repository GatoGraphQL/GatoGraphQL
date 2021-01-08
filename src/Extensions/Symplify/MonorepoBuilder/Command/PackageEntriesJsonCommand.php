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
use Symplify\PackageBuilder\Console\ShellCode;

final class PackageEntriesJsonCommand extends AbstractSymplifyCommand
{
    private PackageEntriesJsonProvider $packageEntriesJsonProvider;

    public function __construct(PackageEntriesJsonProvider $packageEntriesJsonProvider)
    {
        $this->packageEntriesJsonProvider = $packageEntriesJsonProvider;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Provides package entries in json format. Useful for GitHub Actions Workflow');
        $this->addOption(
            Option::FILTER,
            null,
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            'Filter the packages to those from the list of files. Useful to split monorepo on modified packages only',
            []
        );
        $this->addOption(
            Option::FILTER_LIST,
            null,
            InputOption::VALUE_REQUIRED,
            'Space-separate list of files to filter the packages by. Useful to split monorepo on modified packages only',
            ''
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // 2 ways to filter packages:
        // - many single files doing --filter: --filter=file1 --filter=file2
        // - many files together doing --filter-list: --filter-list="file1 file2"
        /** @var string[] $fileFilter */
        $fileFilter = $input->getOption(Option::FILTER);
        /** @var string $fileListFilter */
        $fileListFilter = $input->getOption(Option::FILTER_LIST);
        if ($fileListFilter !== '') {
            $fileFilter = array_merge(
                $fileFilter,
                explode(' ', $fileListFilter)
            );
        }

        $packageEntries = $this->packageEntriesJsonProvider->providePackageEntries($fileFilter);

        // must be without spaces, otherwise it breaks GitHub Actions json
        $json = Json::encode($packageEntries);
        $this->symfonyStyle->writeln($json);

        return ShellCode::SUCCESS;
    }
}
