<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager;
use Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use Symplify\MonorepoBuilder\Testing\ComposerJsonRepositoriesUpdater;
use Symplify\MonorepoBuilder\Testing\ValueObject\Option;
use Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use Symplify\PackageBuilder\Console\Command\CommandNaming;
use Symplify\SmartFileSystem\SmartFileInfo;

use function is_string;
use function preg_match;

final class SymlinkLocalPackageCommand extends AbstractSymplifyCommand
{
    public function __construct(
        private ComposerJsonProvider $composerJsonProvider,
        private ComposerJsonRepositoriesUpdater $composerJsonRepositoriesUpdater,
        private JsonFileManager $jsonFileManager
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(CommandNaming::classToName(self::class));
        $this->setDescription('Symlink to the local package source files');
        $this->addArgument(
            Option::PACKAGE_COMPOSER_JSON,
            InputArgument::REQUIRED,
            'Path to the package\'s "composer(.local).json"'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string */
        $packageComposerJson = $input->getArgument(Option::PACKAGE_COMPOSER_JSON);
        $this->fileSystemGuard->ensureFileExists($packageComposerJson, __METHOD__);

        $packageComposerJsonFileInfo = new SmartFileInfo($packageComposerJson);
        $rootComposerJson = $this->composerJsonProvider->getRootComposerJson();

        // Add "repository" entry in composer.json
        // $symlink => `true` is needed to point to local packages
        // during development, avoiding Packagist
        $this->composerJsonRepositoriesUpdater->processPackage(
            $packageComposerJsonFileInfo,
            $rootComposerJson,
            true
        );

        $this->pinPlatformPHPVersion($packageComposerJsonFileInfo);

        $message = sprintf(
            'Package paths in "%s" have been updated',
            $packageComposerJsonFileInfo->getRelativeFilePathFromCwd()
        );
        $this->symfonyStyle->success($message);

        return self::SUCCESS;
    }

    /**
     * Resolve the dependencies for the minimum PHP version the package
     * supports, and not for the PHP running Composer: the host's newer PHP
     * would pick dependency versions that the webserver (and the built
     * plugin, whose dependencies are resolved on that minimum) cannot run.
     */
    private function pinPlatformPHPVersion(SmartFileInfo $packageComposerJsonFileInfo): void
    {
        $packageComposerJson = $this->jsonFileManager->loadFromFileInfo($packageComposerJsonFileInfo);
        $phpVersionConstraint = $packageComposerJson['require']['php'] ?? null;
        if (
            !is_string($phpVersionConstraint)
            || preg_match('/\d+\.\d+(\.\d+)?/', $phpVersionConstraint, $matches) !== 1
        ) {
            return;
        }
        $packageComposerJson['config']['platform']['php'] = $matches[0];
        $this->jsonFileManager->printJsonToFileInfo($packageComposerJson, $packageComposerJsonFileInfo);
    }
}
