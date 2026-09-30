<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Command;

use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Utils\VersionUtils;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\MonorepoBuilder\Release\Configuration\StageResolver;
use Symplify\MonorepoBuilder\Release\Configuration\VersionResolver;
use Symplify\MonorepoBuilder\Release\Output\ReleaseWorkerReporter;
use Symplify\MonorepoBuilder\Release\ReleaseWorkerProvider;
use Symplify\MonorepoBuilder\Release\ValueObject\SemVersion;
use Symplify\MonorepoBuilder\Release\ValueObject\Stage;
use Symplify\MonorepoBuilder\Validator\SourcesPresenceValidator;
use Symplify\MonorepoBuilder\ValueObject\File;
use Symplify\MonorepoBuilder\ValueObject\Option;
use Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use Symplify\PackageBuilder\Console\Command\CommandNaming;

/**
 * Same as Symplify's "release" command, adding option
 * "--next-version-major" to open the next "major" version
 * after the release (eg: 19.3.0 => 20.0.0-dev), instead
 * of the next "minor" one (eg: 19.3.0 => 19.4.0-dev).
 *
 * @see \Symplify\MonorepoBuilder\Release\Command\ReleaseCommand
 */
final class CustomReleaseCommand extends AbstractSymplifyCommand
{
    /**
     * @var string
     */
    private const NEXT_VERSION_MAJOR_OPTION = 'next-version-major';

    public function __construct(
        private ReleaseWorkerProvider $releaseWorkerProvider,
        private SourcesPresenceValidator $sourcesPresenceValidator,
        private StageResolver $stageResolver,
        private VersionResolver $versionResolver,
        private ReleaseWorkerReporter $releaseWorkerReporter,
        private VersionUtils $versionUtils,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(CommandNaming::classToName(self::class));
        $this->setDescription('Perform release process with set Release Workers.');

        $description = sprintf(
            'Release version, in format "<major>.<minor>.<patch>" or "v<major>.<minor>.<patch> or one of keywords: "%s"',
            implode('", "', SemVersion::ALL)
        );
        $this->addArgument(Option::VERSION, InputArgument::REQUIRED, $description);

        $this->addOption(
            Option::DRY_RUN,
            null,
            InputOption::VALUE_NONE,
            'Do not perform operations, just their preview'
        );

        $this->addOption(Option::STAGE, null, InputOption::VALUE_REQUIRED, 'Name of stage to perform', Stage::MAIN);

        $this->addOption(
            self::NEXT_VERSION_MAJOR_OPTION,
            null,
            InputOption::VALUE_NONE,
            'Open the next "major" version after the release (eg: 19.3.0 => 20.0.0-dev), not the "minor" one'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->sourcesPresenceValidator->validateRootComposerJsonName();

        $this->versionUtils->setNextVersionIsMajor((bool) $input->getOption(self::NEXT_VERSION_MAJOR_OPTION));

        $stage = $this->stageResolver->resolveFromInput($input);

        $releaseWorkers = $this->releaseWorkerProvider->provideByStage($stage);
        if ($releaseWorkers === []) {
            $errorMessage = sprintf(
                'There are no release workers registered. Be sure to add them to "%s"',
                File::CONFIG
            );
            $this->symfonyStyle->error($errorMessage);

            return self::FAILURE;
        }

        $totalWorkerCount = count($releaseWorkers);
        $i = 0;
        $isDryRun = (bool) $input->getOption(Option::DRY_RUN);
        $version = $this->versionResolver->resolveVersion($input, $stage);

        foreach ($releaseWorkers as $releaseWorker) {
            $title = sprintf('%d/%d) ', ++$i, $totalWorkerCount) . $releaseWorker->getDescription($version);
            $this->symfonyStyle->title($title);
            $this->releaseWorkerReporter->printMetadata($releaseWorker);

            if (! $isDryRun) {
                $releaseWorker->work($version);
            }
        }

        if ($isDryRun) {
            $this->symfonyStyle->note('Running in dry mode, nothing is changed');
        } elseif ($stage === Stage::MAIN) {
            $message = sprintf('Version "%s" is now released!', $version->getVersionString());
            $this->symfonyStyle->success($message);
        } else {
            $finishedMessage = sprintf(
                'Stage "%s" for version "%s" is now finished!',
                $stage,
                $version->getVersionString()
            );
            $this->symfonyStyle->success($finishedMessage);
        }

        return self::SUCCESS;
    }
}
