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
        $this->addOption(
            Option::FILTER,
            null,
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            'Filter the entries by type, to those from extensions/bundles/standalone plugins. Useful to only generate bundles in GitHub Actions',
            []
        );
        $this->addOption(
            Option::SLUG,
            null,
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            'Filter the entries by plugin slug. Useful to only generate bundles in GitHub Actions',
            []
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $scopedOnly = (bool) $input->getOption(Option::SCOPED_ONLY);
        /** @var string[] */
        $extensionTypeFilter = $input->getOption(Option::FILTER);
        /** @var string[] */
        $extensionSlugFilter = $input->getOption(Option::SLUG);
        $pluginConfigEntries = $this->pluginConfigEntriesJsonProvider->providePluginConfigEntries($scopedOnly, $extensionTypeFilter, $extensionSlugFilter);

        // must be without spaces, otherwise it breaks GitHub Actions json
        $json = Json::encode($pluginConfigEntries);
        $this->symfonyStyle->writeln($json);

        return self::SUCCESS;
    }
}
