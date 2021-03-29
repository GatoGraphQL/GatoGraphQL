<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Command;

use Nette\Utils\Json;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Json\PluginConfigEntriesJsonProvider;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use Symplify\PackageBuilder\Console\ShellCode;

final class PluginConfigEntriesJsonCommand extends AbstractSymplifyCommand
{
    protected function configure(): void
    {
        $this->setDescription('Provides plugin configuration entries in json format. Useful for GitHub Actions Workflow');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $pluginConfigEntries = $this->pluginConfigEntriesJsonProvider->providePluginConfigEntries();

        // must be without spaces, otherwise it breaks GitHub Actions json
        $json = Json::encode($pluginConfigEntries);
        $this->symfonyStyle->writeln($json);

        return ShellCode::SUCCESS;
    }
}
