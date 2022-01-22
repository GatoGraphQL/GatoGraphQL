<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Command;

use Nette\Utils\Json;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Json\PluginConfigEntriesJsonProvider;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\ValueObject\Option;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use Symplify\PackageBuilder\Console\Command\CommandNaming;
use Symplify\PackageBuilder\Console\ShellCode;

final class PluginConfigEntriesJsonCommand extends AbstractSymplifyCommand
{
    public function __construct(private PluginConfigEntriesJsonProvider $pluginConfigEntriesJsonProvider)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(CommandNaming::classToName(self::class));
        $this->setDescription('Provides plugin configuration entries in json format. Useful for GitHub Actions Workflow');
        $this->addOption(
            Option::SCOPED_ONLY,
            null,
            InputOption::VALUE_NONE,
            'Only fetch releases that must be scoped.'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $scopedOnly = (bool) $input->getOption(Option::SCOPED_ONLY);
        $pluginConfigEntries = $this->pluginConfigEntriesJsonProvider->providePluginConfigEntries($scopedOnly);

        // must be without spaces, otherwise it breaks GitHub Actions json
        $json = Json::encode($pluginConfigEntries);
        $this->symfonyStyle->writeln($json);

        return ShellCode::SUCCESS;
    }
}
