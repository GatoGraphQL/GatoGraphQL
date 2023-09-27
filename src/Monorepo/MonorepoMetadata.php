<?php

declare(strict_types=1);

namespace PoP\PoP\Monorepo;

final class MonorepoMetadata
{
    /**
     * Modify this const when bumping the code to a new version.
     *
     * Important: This code is read-only! A ReleaseWorker
     * will search for this pattern using a regex, to update the
     * version when creating a new release
     * (i.e. via `composer release-major|minor|patch`).
     */
    final public const VERSION = '1.0.9';

    final public const GIT_BASE_BRANCH = 'master';
    final public const GIT_USER_NAME = 'leoloso';
    final public const GIT_USER_EMAIL = 'leo@getpop.org';

    final public const GITHUB_REPO_OWNER = 'GatoGraphQL';
    final public const GITHUB_REPO_NAME = 'GatoGraphQL';
}
