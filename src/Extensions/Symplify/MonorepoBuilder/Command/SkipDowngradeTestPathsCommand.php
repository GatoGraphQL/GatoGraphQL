<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Command;

use PoP\PoP\Extensions\Symplify\MonorepoBuilder\ValueObject\Option;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use Symplify\PackageBuilder\Console\Command\CommandNaming;
use Symplify\PackageBuilder\Parameter\ParameterProvider;

final class SkipDowngradeTestPathsCommand extends AbstractSymplifyCommand
{
    /**
     * @var array<string, string>
     */
    private array $skipDowngradeTestFiles = [];

    public function __construct(
        ParameterProvider $parameterProvider
    ) {
        parent::__construct();
        $this->skipDowngradeTestFiles = $parameterProvider->provideArrayParameter(Option::SKIP_DOWNGRADE_TEST_FILES);
    }

    protected function configure(): void
    {
        $this->setName(CommandNaming::classToName(self::class));
        $this->setDescription('Files that must be skiped from testing the downgrade');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $skipDowngradeTestFiles = implode(' ', $this->skipDowngradeTestFiles);

        $this->symfonyStyle->writeln($skipDowngradeTestFiles);

        return self::SUCCESS;
    }
}
