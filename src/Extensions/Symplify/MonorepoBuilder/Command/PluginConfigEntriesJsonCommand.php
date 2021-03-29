<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Command;

use Nette\Utils\Json;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\ValueObject\Option;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use Symplify\PackageBuilder\Console\ShellCode;
use Symplify\PackageBuilder\Parameter\ParameterProvider;

final class PluginConfigEntriesJsonCommand extends AbstractSymplifyCommand
{
    /**
     * @var array<string, string>
     */
    private array $pluginConfigEntries = [];

    public function __construct(
        ParameterProvider $parameterProvider
    ) {
        parent::__construct();
        $this->pluginConfigEntries = $parameterProvider->provideArrayParameter(Option::PLUGIN_CONFIG_ENTRIES);
    }

    protected function configure(): void
    {
        $this->setDescription('Provides plugin configuration entries in json format. Useful for GitHub Actions Workflow');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // must be without spaces, otherwise it breaks GitHub Actions json
        $json = Json::encode($this->pluginConfigEntries);
        $this->symfonyStyle->writeln($json);

        return ShellCode::SUCCESS;
    }
}
