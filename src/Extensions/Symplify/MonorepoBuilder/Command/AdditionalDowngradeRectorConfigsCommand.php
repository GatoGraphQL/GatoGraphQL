<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Command;

use PoP\PoP\Extensions\Symplify\MonorepoBuilder\ValueObject\Option;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use Symplify\PackageBuilder\Console\Command\CommandNaming;
use Symplify\PackageBuilder\Console\ShellCode;
use Symplify\PackageBuilder\Parameter\ParameterProvider;

final class AdditionalDowngradeRectorConfigsCommand extends AbstractSymplifyCommand
{
    /**
     * @var array<string, string>
     */
    private array $additionalDowngradeRectorConfigs = [];

    public function __construct(
        ParameterProvider $parameterProvider
    ) {
        parent::__construct();
        $this->additionalDowngradeRectorConfigs = $parameterProvider->provideArrayParameter(Option::ADDITIONAL_DOWNGRADE_RECTOR_CONFIGS);
    }

    protected function configure(): void
    {
        $this->setName(CommandNaming::classToName(self::class));
        // Hack to fix bug: https://github.com/rectorphp/rector/issues/5962
        $this->setDescription('Space-separated list of additional downgrade Rector config files');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $additionalDowngradeRectorConfigs = implode(' ', $this->additionalDowngradeRectorConfigs);

        $this->symfonyStyle->writeln($additionalDowngradeRectorConfigs);

        return ShellCode::SUCCESS;
    }
}
