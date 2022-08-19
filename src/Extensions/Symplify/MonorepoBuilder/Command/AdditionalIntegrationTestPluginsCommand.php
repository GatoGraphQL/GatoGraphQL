<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Command;

use PoP\PoP\Extensions\Symplify\MonorepoBuilder\ValueObject\Option;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use Symplify\PackageBuilder\Console\Command\CommandNaming;
use Symplify\PackageBuilder\Parameter\ParameterProvider;

final class AdditionalIntegrationTestPluginsCommand extends AbstractSymplifyCommand
{
    /**
     * @var array<string,string>
     */
    private array $additionalIntegrationTestPlugins = [];

    public function __construct(
        ParameterProvider $parameterProvider
    ) {
        parent::__construct();
        $this->additionalIntegrationTestPlugins = $parameterProvider->provideArrayParameter(Option::ADDITIONAL_INTEGRATION_TEST_PLUGINS);
    }

    protected function configure(): void
    {
        $this->setName(CommandNaming::classToName(self::class));
        $this->setDescription('JSON-encoded list of additional plugins to install in the webserver (eg: InstaWP) for executing integration tests');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $additionalIntegrationTestPlugins = json_encode($this->additionalIntegrationTestPlugins);

        $this->symfonyStyle->writeln($additionalIntegrationTestPlugins);

        return self::SUCCESS;
    }
}
