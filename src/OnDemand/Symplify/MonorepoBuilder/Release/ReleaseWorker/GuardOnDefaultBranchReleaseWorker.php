<?php

declare(strict_types=1);

namespace PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PharIo\Version\Version;
use PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Worker\AbstractGuardOnDefaultBranchWorker;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;

final class GuardOnDefaultBranchReleaseWorker extends AbstractGuardOnDefaultBranchWorker implements ReleaseWorkerInterface
{
    public function work(Version $version): void
    {
        $this->doWork();
    }

    public function getDescription(Version $version): string
    {
        return $this->doGetDescription();
    }
}
