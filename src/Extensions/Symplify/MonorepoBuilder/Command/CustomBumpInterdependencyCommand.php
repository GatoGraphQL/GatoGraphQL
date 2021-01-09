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
use Symplify\PackageBuilder\Console\ShellCode;
use Symplify\SymplifyKernel\Exception\ShouldNotHappenException;

final class CustomBumpInterdependencyCommand extends AbstractSymplifyCommand
{
    /**
     * @var string
     */
    private const VERSION_ARGUMENT = 'version';

    /**
     * @var CustomDependencyUpdater
     */
    private $customDependencyUpdater;

    /**
     * @var ComposerJsonProvider
     */
    private $composerJsonProvider;

    /**
     * @var SourcesPresenceValidator
     */
    private $sourcesPresenceValidator;

    public function __construct(
        CustomDependencyUpdater $customDependencyUpdater,
        ComposerJsonProvider $composerJsonProvider,
        SourcesPresenceValidator $sourcesPresenceValidator
    ) {
        parent::__construct();

        $this->customDependencyUpdater = $customDependencyUpdater;
        $this->composerJsonProvider = $composerJsonProvider;
        $this->sourcesPresenceValidator = $sourcesPresenceValidator;
    }

    protected function configure(): void
    {
        $this->setDescription('Bump dependency of split packages on each other');
        $this->addArgument(
            self::VERSION_ARGUMENT,
            InputArgument::REQUIRED,
            'New version of inter-dependencies, e.g. "^4.4.2"'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->sourcesPresenceValidator->validateRootComposerJsonName();

        /** @var string $version */
        $version = $input->getArgument(self::VERSION_ARGUMENT);

        $rootComposerJson = $this->composerJsonProvider->getRootComposerJson();

        $packageReplacements = array_keys($rootComposerJson->getReplace());
        if ($packageReplacements === []) {
            throw new ShouldNotHappenException();
        }

        $this->customDependencyUpdater->updateFileInfosWithVendorAndVersion(
            $this->composerJsonProvider->getPackagesComposerFileInfos(),
            $packageReplacements,
            $version
        );

        $successMessage = sprintf('Inter-dependencies of packages were updated to "%s".', $version);
        $this->symfonyStyle->success($successMessage);

        return ShellCode::SUCCESS;
    }
}
