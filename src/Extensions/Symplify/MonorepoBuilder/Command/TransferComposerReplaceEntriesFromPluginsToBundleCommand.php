<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Command;

use PoP\PoP\Extensions\Symplify\MonorepoBuilder\ComposerReplaceEntriesRelocator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use Symplify\MonorepoBuilder\Validator\SourcesPresenceValidator;
use Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use Symplify\PackageBuilder\Console\Command\CommandNaming;

final class TransferComposerReplaceEntriesFromPluginsToBundleCommand extends AbstractSymplifyCommand
{
    /**
     * @var string
     */
    private const BUNDLE_COMPOSER_PATH = 'bundle-composer-path';
    /**
     * @var string
     */
    private const EXCLUDE_REPLACE = 'exclude-replace';

    public function __construct(
        private ComposerReplaceEntriesRelocator $composerReplaceEntriesRelocator,
        private ComposerJsonProvider $composerJsonProvider,
        private SourcesPresenceValidator $sourcesPresenceValidator
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(CommandNaming::classToName(self::class));
        $this->setDescription('Bundles: Transfer the "replace" entries in composer.json, from the contained plugins to the bundle');
        $this->addArgument(
            self::BUNDLE_COMPOSER_PATH,
            InputArgument::REQUIRED,
            'Path to the bundle\'s "composer.json" file'
        );
        $this->addOption(
            self::EXCLUDE_REPLACE,
            null,
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            'Packages that must not be added to the bundle\'s "replace" section.',
            []
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->sourcesPresenceValidator->validateRootComposerJsonName();

        /** @var string */
        $bundleComposerPath = $input->getArgument(self::BUNDLE_COMPOSER_PATH);

        /** @var string[] */
        $excludeReplacePackageNames = $input->getOption(self::EXCLUDE_REPLACE);

        $this->composerReplaceEntriesRelocator->moveReplaceEntriesFromPluginsToBundle(
            $this->composerJsonProvider->getPackagesComposerFileInfos(),
            $bundleComposerPath,
            $excludeReplacePackageNames
        );

        $successMessage = sprintf('All "replace" entries in the contained plugins\' composer.json were moved to "%s".', $bundleComposerPath);
        $this->symfonyStyle->success($successMessage);

        return self::SUCCESS;
    }
}
