<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Command;

use PharIo\Version\Version;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Utils\VersionUtils;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\ValueObject\Option as CustomOption;
use RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use Symplify\MonorepoBuilder\Release\Output\ReleaseWorkerReporter;
use Symplify\MonorepoBuilder\Release\ReleaseWorkerProvider;
use Symplify\MonorepoBuilder\ValueObject\Option;
use Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use Symplify\PackageBuilder\Console\Command\CommandNaming;
use Symplify\PackageBuilder\Parameter\ParameterProvider;

use function array_unique;
use function array_values;
use function count;
use function file_get_contents;
use function get_class;
use function getcwd;
use function implode;
use function in_array;
use function preg_match;
use function sprintf;
use function str_ends_with;
use function strlen;
use function substr;

/**
 * Open the next dev version without a release
 * (eg: 20.1.0-dev => 21.0.0-dev for "major",
 * or 20.1.0-dev => 20.2.0-dev for "minor"),
 * running only the release workers that open
 * the next dev version.
 *
 * It creates no tag, and doesn't commit or push.
 */
final class BumpDevVersionCommand extends AbstractSymplifyCommand
{
    /**
     * @var string
     */
    private const BUMP_ARGUMENT = 'bump';
    /**
     * @var string
     */
    private const BUMP_MAJOR = 'major';
    /**
     * @var string
     */
    private const BUMP_MINOR = 'minor';
    /**
     * @var string
     */
    private const DEV_SUFFIX = '-dev';

    public function __construct(
        private ReleaseWorkerProvider $releaseWorkerProvider,
        private ReleaseWorkerReporter $releaseWorkerReporter,
        private VersionUtils $versionUtils,
        private ParameterProvider $parameterProvider,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(CommandNaming::classToName(self::class));
        $this->setDescription('Open the next dev version, without a release: no tag, commit or push.');

        $this->addArgument(
            self::BUMP_ARGUMENT,
            InputArgument::REQUIRED,
            sprintf(
                'Version to open: "%s" (eg: 20.1.0-dev => 21.0.0-dev) or "%s" (eg: 20.1.0-dev => 20.2.0-dev)',
                self::BUMP_MAJOR,
                self::BUMP_MINOR
            )
        );

        $this->addOption(
            Option::DRY_RUN,
            null,
            InputOption::VALUE_NONE,
            'Do not perform operations, just their preview'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string */
        $bump = $input->getArgument(self::BUMP_ARGUMENT);
        if (!in_array($bump, [self::BUMP_MAJOR, self::BUMP_MINOR], true)) {
            $this->symfonyStyle->error(sprintf(
                'The argument must be "%s" or "%s", but it is "%s"',
                self::BUMP_MAJOR,
                self::BUMP_MINOR,
                $bump
            ));
            return self::FAILURE;
        }

        try {
            $currentDevVersion = $this->getCurrentDevVersion();
        } catch (RuntimeException $e) {
            $this->symfonyStyle->error($e->getMessage());
            return self::FAILURE;
        }

        if (!str_ends_with($currentDevVersion, self::DEV_SUFFIX)) {
            $this->symfonyStyle->error(sprintf(
                'The current version "%s" is not a dev version, as it doesn\'t end in "%s"',
                $currentDevVersion,
                self::DEV_SUFFIX
            ));
            return self::FAILURE;
        }

        /**
         * The release workers take the released version, and open
         * the dev version that follows it. So pass them the current
         * dev version without "-dev" (eg: 20.1.0-dev => 20.1.0).
         */
        $version = new Version(substr($currentDevVersion, 0, strlen($currentDevVersion) - strlen(self::DEV_SUFFIX)));
        $this->versionUtils->setNextVersionIsMajor($bump === self::BUMP_MAJOR);
        $this->versionUtils->setUpstreamVersion($version);

        $releaseWorkers = $this->getBumpDevVersionReleaseWorkers();
        if ($releaseWorkers === []) {
            $this->symfonyStyle->error('There are no release workers to open the next dev version');
            return self::FAILURE;
        }

        $nextDevVersion = $this->versionUtils->getNextDevVersion($version);
        $this->symfonyStyle->note(sprintf(
            'Bumping the dev version from "%s" to "%s"',
            $currentDevVersion,
            $nextDevVersion
        ));

        $totalWorkerCount = count($releaseWorkers);
        $i = 0;
        $isDryRun = (bool) $input->getOption(Option::DRY_RUN);
        foreach ($releaseWorkers as $releaseWorker) {
            $title = sprintf('%d/%d) ', ++$i, $totalWorkerCount) . $releaseWorker->getDescription($version);
            $this->symfonyStyle->title($title);
            $this->releaseWorkerReporter->printMetadata($releaseWorker);

            if (!$isDryRun) {
                $releaseWorker->work($version);
            }
        }

        if ($isDryRun) {
            $this->symfonyStyle->note('Running in dry mode, nothing is changed');
            return self::SUCCESS;
        }

        $this->symfonyStyle->success(sprintf(
            'The dev version is now "%s". Review the changes, then commit them',
            $nextDevVersion
        ));

        return self::SUCCESS;
    }

    /**
     * Read the version from the "Version:" header in the plugin
     * main files, which must all have the same one.
     *
     * @throws RuntimeException If a file has no version, or the files have different versions
     */
    private function getCurrentDevVersion(): string
    {
        /** @var array<array<string,mixed>> */
        $pluginConfigEntries = $this->parameterProvider->provideArrayParameter(CustomOption::PLUGIN_CONFIG_ENTRIES);
        $rootDir = (string) getcwd();
        $versions = [];
        foreach ($pluginConfigEntries as $pluginConfigEntry) {
            $pluginMainFile = $rootDir . '/' . $pluginConfigEntry['path'] . '/' . $pluginConfigEntry['main_file'];
            $content = (string) file_get_contents($pluginMainFile);
            $matches = [];
            if (!preg_match('/\bVersion:\s+([a-z0-9.-]+)/', $content, $matches)) {
                throw new RuntimeException(sprintf(
                    'The plugin main file "%s" has no "Version:" header',
                    $pluginMainFile
                ));
            }
            $versions[] = $matches[1];
        }

        $versions = array_values(array_unique($versions));
        if ($versions === []) {
            throw new RuntimeException('There are no plugin main files to read the version from');
        }
        if (count($versions) > 1) {
            throw new RuntimeException(sprintf(
                'The plugin main files have different versions: "%s"',
                implode('", "', $versions)
            ));
        }
        return $versions[0];
    }

    /**
     * The release workers in the order of the configured classes
     *
     * @return ReleaseWorkerInterface[]
     */
    private function getBumpDevVersionReleaseWorkers(): array
    {
        /** @var string[] */
        $releaseWorkerClasses = $this->parameterProvider->provideArrayParameter(CustomOption::BUMP_DEV_VERSION_RELEASE_WORKER_CLASSES);

        $releaseWorkersByClass = [];
        foreach ($this->releaseWorkerProvider->provide() as $releaseWorker) {
            $releaseWorkersByClass[get_class($releaseWorker)] = $releaseWorker;
        }

        $releaseWorkers = [];
        foreach ($releaseWorkerClasses as $releaseWorkerClass) {
            if (!isset($releaseWorkersByClass[$releaseWorkerClass])) {
                continue;
            }
            $releaseWorkers[] = $releaseWorkersByClass[$releaseWorkerClass];
        }
        return $releaseWorkers;
    }
}
