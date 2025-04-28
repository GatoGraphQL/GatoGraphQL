<?php

declare(strict_types=1);

namespace PoP\PoP\Monorepo;

final class MonorepoMetadata
{
    /**
     * This const will reflect the current version of the monorepo.
     *
     * Important: This code is read-only! A ReleaseWorker
     * will search for this pattern using a regex, to update the
     * version when creating a new release
     * (i.e. via `composer release-major|minor|patch`).
     *
     * @gatographql-readonly-code
     */
    final public const VERSION = '12.1.0-dev';
    /**
     * This const will reflect the latest published tag in GitHub.
     *
     * Important: This code is read-only! A ReleaseWorker
     * will search for this pattern using a regex, to update the
     * version when creating a new release
     * (i.e. via `composer release-major|minor|patch`).
     *
     * @gatographql-readonly-code
     */
    final public const LATEST_PROD_VERSION = '12.0.0';

    final public const GIT_BASE_BRANCH = 'master';
    final public const GIT_USER_NAME = 'leoloso';
    final public const GIT_USER_EMAIL = 'leo@getpop.org';

    final public const GITHUB_REPO_OWNER = 'GatoGraphQL';
    final public const GITHUB_REPO_NAME = 'GatoGraphQL';
}
