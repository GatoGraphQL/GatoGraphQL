<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Command;

use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Json\SkipMonorepoSplitPackagesProvider;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use Symplify\PackageBuilder\Console\Command\CommandNaming;

final class SkipMonorepoSplitPackagesCommand extends AbstractSymplifyCommand
{
    public function __construct(private SkipMonorepoSplitPackagesProvider $skipMonorepoSplitPackagesProvider)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(CommandNaming::classToName(self::class));
        $this->setDescription('Provides packages to skip splitting the monorepo to, in json format. Useful for GitHub Actions Workflow');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $skipMonorepoSplitPackages = $this->skipMonorepoSplitPackagesProvider->provideSkipMonorepoSplitPackages();

        $this->symfonyStyle->writeln(implode(' ', $skipMonorepoSplitPackages));

        return self::SUCCESS;
    }
}
