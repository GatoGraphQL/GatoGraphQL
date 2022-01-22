<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Command;

use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Json\LocalPackageOwnersProvider;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use Symplify\PackageBuilder\Console\Command\CommandNaming;
use Symplify\PackageBuilder\Console\ShellCode;

final class LocalPackageOwnersCommand extends AbstractSymplifyCommand
{
    public function __construct(
        private LocalPackageOwnersProvider $packageOwnersProvider,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(CommandNaming::classToName(self::class));
        $this->setDescription('Space-separated list of local package owners in the monorepo');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $localPackageOwners = $this->packageOwnersProvider->provideLocalPackageOwners();

        $this->symfonyStyle->writeln(implode(' ', $localPackageOwners));

        return self::SUCCESS;
    }
}
