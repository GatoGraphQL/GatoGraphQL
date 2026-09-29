<?php

declare(strict_types=1);

namespace PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PharIo\Version\Version;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\Utils\VersionUtils;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use Symplify\MonorepoBuilder\Release\Process\ProcessRunner;
use Symplify\MonorepoBuilder\ValueObject\Option;
use Symplify\PackageBuilder\Parameter\ParameterProvider;

/**
 * Same as Symplify's worker, but using the custom VersionUtils,
 * which can also open the next "major" version.
 *
 * @see \Symplify\MonorepoBuilder\Release\ReleaseWorker\PushNextDevReleaseWorker
 */
class PushNextDevReleaseWorker implements ReleaseWorkerInterface
{
    private string $branchName;

    public function __construct(
        private ProcessRunner $processRunner,
        private VersionUtils $versionUtils,
        ParameterProvider $parameterProvider
    ) {
        $this->branchName = $parameterProvider->provideStringParameter(Option::DEFAULT_BRANCH_NAME);
    }

    public function work(Version $version): void
    {
        $versionInString = $this->getVersionDev($version);

        $gitAddCommitCommand = sprintf(
            'git add . && git commit --allow-empty -m "open %s" && git push origin "%s"',
            $versionInString,
            $this->branchName
        );

        $this->processRunner->run($gitAddCommitCommand);
    }

    public function getDescription(Version $version): string
    {
        $versionInString = $this->getVersionDev($version);

        return sprintf('Push "%s" open to remote repository', $versionInString);
    }

    private function getVersionDev(Version $version): string
    {
        return $this->versionUtils->getNextAliasFormat($version);
    }
}
