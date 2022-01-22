<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use Symplify\MonorepoBuilder\Testing\ComposerJsonRepositoriesUpdater;
use Symplify\MonorepoBuilder\Testing\ValueObject\Option;
use Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use Symplify\PackageBuilder\Console\Command\CommandNaming;
use Symplify\PackageBuilder\Console\ShellCode;
use Symplify\SmartFileSystem\SmartFileInfo;

final class SymlinkLocalPackageCommand extends AbstractSymplifyCommand
{
    public function __construct(
        private ComposerJsonProvider $composerJsonProvider,
        private ComposerJsonRepositoriesUpdater $composerJsonRepositoriesUpdater
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

        $message = sprintf(
            'Package paths in "%s" have been updated',
            $packageComposerJsonFileInfo->getRelativeFilePathFromCwd()
        );
        $this->symfonyStyle->success($message);

        return ShellCode::SUCCESS;
    }
}
