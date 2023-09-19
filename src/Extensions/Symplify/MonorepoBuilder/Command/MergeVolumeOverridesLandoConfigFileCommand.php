<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Command;

use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Neon\NeonFilePrinter;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\LandoVolumeOverridesYamlContentProvider;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\ValueObject\Option;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use Symplify\PackageBuilder\Console\Command\CommandNaming;

final class MergeVolumeOverridesLandoConfigFileCommand extends AbstractSymplifyCommand
{
    protected string|int $level;

    public function __construct(
        private LandoVolumeOverridesYamlContentProvider $landoVolumeOverridesConfigYamlContentProvider,
        private NeonFilePrinter $neonFilePrinter,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(CommandNaming::classToName(self::class));
        $this->setDescription('Create the Lando config with the volume overrides, including the mappings for all packages');
        $this->addOption(
            Option::OUTPUT_FILE,
            null,
            InputOption::VALUE_REQUIRED,
            'Path to dump Lando config file'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $neonFileContent = $this->landoVolumeOverridesConfigYamlContentProvider->provideContent();

        $outputFile = (string) $input->getOption(Option::OUTPUT_FILE);
        $outputFilePath = getcwd() . DIRECTORY_SEPARATOR . $outputFile;
        $this->neonFilePrinter->printContentToOutputFile($neonFileContent, $outputFilePath);

        return self::SUCCESS;
    }
}
