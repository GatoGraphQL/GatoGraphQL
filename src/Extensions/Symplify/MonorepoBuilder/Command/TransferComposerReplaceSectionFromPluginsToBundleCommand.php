<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Command;

use PoP\PoP\Extensions\Symplify\MonorepoBuilder\CustomDependencyUpdater;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use Symplify\MonorepoBuilder\Validator\SourcesPresenceValidator;
use Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use Symplify\PackageBuilder\Console\Command\CommandNaming;
use Symplify\SymplifyKernel\Exception\ShouldNotHappenException;

final class TransferComposerReplaceSectionFromPluginsToBundleCommand extends AbstractSymplifyCommand
{
    /**
     * @var string
     */
    private const ROOT_COMPOSER_PATH = 'root-composer-path';

    public function __construct(
        private CustomDependencyUpdater $customDependencyUpdater,
        private ComposerJsonProvider $composerJsonProvider,
        private SourcesPresenceValidator $sourcesPresenceValidator
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(CommandNaming::classToName(self::class));
        $this->setDescription('Transfer the "replace" entries in composer.json, from dependency packages to the root package');
        $this->addArgument(
            self::ROOT_COMPOSER_PATH,
            InputArgument::REQUIRED,
            'Path to the root "composer.json" file'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->sourcesPresenceValidator->validateRootComposerJsonName();

        /** @var string $rootComposerPath */
        $rootComposerPath = $input->getArgument(self::ROOT_COMPOSER_PATH);

        $rootComposerJson = $this->composerJsonProvider->getRootComposerJson();

        $packageReplacements = array_keys($rootComposerJson->getReplace());
        if ($packageReplacements === []) {
            throw new ShouldNotHappenException();
        }

        $this->customDependencyUpdater->updateFileInfosWithVendorAndVersion(
            $this->composerJsonProvider->getPackagesComposerFileInfos(),
            $packageReplacements,
            $rootComposerPath
        );

        $successMessage = sprintf('Inter-dependencies of packages were updated to "%s".', $rootComposerPath);
        $this->symfonyStyle->success($successMessage);

        return self::SUCCESS;
    }
}
