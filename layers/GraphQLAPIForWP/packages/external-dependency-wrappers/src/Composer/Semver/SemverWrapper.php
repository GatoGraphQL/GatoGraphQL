<?php

declare(strict_types=1);

namespace GatoGraphQL\ExternalDependencyWrappers\Composer\Semver;

use Composer\Semver\Semver;

/**
 * Wrapper for Composer\Semver\Semver.
 *
 * These methods are accessed static, instead of via a service,
 * since they are referenced in ExtensionManager, before
 * the container service has been initialized.
 */
class SemverWrapper
{
    /**
     * Determine if given version satisfies given constraints.
     */
    public static function satisfies(string $version, string $constraints): bool
    {
        return Semver::satisfies($version, $constraints);
    }
}
