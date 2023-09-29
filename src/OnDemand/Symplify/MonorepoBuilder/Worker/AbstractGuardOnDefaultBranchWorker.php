<?php

declare(strict_types=1);

namespace PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Worker;

use Symplify\MonorepoBuilder\Release\Process\ProcessRunner;
use Symplify\MonorepoBuilder\ValueObject\Option;
use Symplify\PackageBuilder\Parameter\ParameterProvider;
use Symplify\SymplifyKernel\Exception\ShouldNotHappenException;

abstract class AbstractGuardOnDefaultBranchWorker
{
    private string $branchName;

    public function __construct(
        private ProcessRunner $processRunner,
        ParameterProvider $parameterProvider
    ) {
        $this->branchName = $parameterProvider->provideStringParameter(Option::DEFAULT_BRANCH_NAME);
    }

    protected function doWork(): void
    {
        $currentBranchName = trim($this->processRunner->run('git branch --show-current'));
        if ($currentBranchName !== $this->branchName) {
            throw new ShouldNotHappenException(sprintf(
                'Switch from branch "%s" to "%s" before doing the release',
                $currentBranchName,
                $this->branchName
            ));
        }
    }

    protected function doGetDescription(): string
    {
        return 'Check we are on the default branch, to avoid commit/push to a different branch';
    }
}
