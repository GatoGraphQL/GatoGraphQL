<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Command;

use Nette\Utils\Json;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Json\InstaWPConfigEntriesJsonProvider;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use Symplify\PackageBuilder\Console\Command\CommandNaming;

final class InstawpConfigEntriesJsonCommand extends AbstractSymplifyCommand
{
    public function __construct(private InstaWPConfigEntriesJsonProvider $instaWPConfigEntriesJsonProvider)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(CommandNaming::classToName(self::class));
        $this->setDescription('Provides InstaWP configuration entries in JSON format. Useful to execute integration tests via a GitHub Actions Workflow');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $instaWPConfigEntries = $this->instaWPConfigEntriesJsonProvider->provideInstaWPConfigEntries();

        // must be without spaces, otherwise it breaks GitHub Actions json
        $json = Json::encode($instaWPConfigEntries);
        $this->symfonyStyle->writeln($json);

        return self::SUCCESS;
    }
}
