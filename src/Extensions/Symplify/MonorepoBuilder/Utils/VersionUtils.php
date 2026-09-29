<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Utils;

use PharIo\Version\Version;
use Symplify\MonorepoBuilder\Utils\VersionUtils as UpstreamVersionUtils;
use Symplify\MonorepoBuilder\ValueObject\Option;
use Symplify\PackageBuilder\Parameter\ParameterProvider;

/**
 * @see \Symplify\MonorepoBuilder\Utils
 */
final class VersionUtils
{
    private string $packageAliasFormat;

    /**
     * When releasing, the next dev version is by default
     * the next "minor" one (eg: 19.3.0 => 19.4.0-dev).
     * Enabling this, it is the next "major" one instead
     * (eg: 19.3.0 => 20.0.0-dev).
     */
    private bool $nextVersionIsMajor = false;

    public function __construct(
        ParameterProvider $parameterProvider,
        private UpstreamVersionUtils $upstreamVersionUtils,
    ) {
        $this->packageAliasFormat = $parameterProvider->provideStringParameter(Option::PACKAGE_ALIAS_FORMAT);
    }

    public function setNextVersionIsMajor(bool $nextVersionIsMajor): void
    {
        $this->nextVersionIsMajor = $nextVersionIsMajor;
    }

    public function getNextVersion(Version | string $version): string
    {
        $requiredNextFormat = $this->getRequiredNextFormat($version);
        return substr(
            $requiredNextFormat,
            strlen('^')
        ) . '.0';
    }

    public function getNextDevVersion(Version | string $version): string
    {
        return $this->getNextVersion($version) . '-dev';
    }

    public function getRequiredNextFormat(Version | string $version): string
    {
        if (!$this->nextVersionIsMajor) {
            return $this->upstreamVersionUtils->getRequiredNextFormat($version);
        }

        $version = $this->normalizeVersion($version);

        return '^' . $this->getNextMajorNumber($version) . '.0';
    }

    public function getNextAliasFormat(Version | string $version): string
    {
        if (!$this->nextVersionIsMajor) {
            return $this->upstreamVersionUtils->getNextAliasFormat($version);
        }

        $version = $this->normalizeVersion($version);

        return str_replace(
            ['<major>', '<minor>'],
            [(string) $this->getNextMajorNumber($version), '0'],
            $this->packageAliasFormat
        );
    }

    public function getRequiredCurrentFormat(Version | string $version): string
    {
        $version = $this->normalizeVersion($version);
        $minor = $this->getCurrentMinorNumber($version);

        return '^' . ($version->getMajor()->getValue() ?? '0') . '.' . $minor;
    }

    public function getCurrentAliasFormat(Version | string $version): string
    {
        $version = $this->normalizeVersion($version);

        $minor = $this->getCurrentMinorNumber($version);

        return str_replace(
            ['<major>', '<minor>'],
            [(string) ($version->getMajor()->getValue() ?? '0'), (string) $minor],
            $this->packageAliasFormat
        );
    }

    private function normalizeVersion(Version | string $version): Version
    {
        if (is_string($version)) {
            return new Version($version);
        }

        return $version;
    }

    private function getCurrentMinorNumber(Version $version): int
    {
        return (int) ($version->getMinor()->getValue() ?? 0);
    }

    private function getNextMajorNumber(Version $version): int
    {
        return (int) ($version->getMajor()->getValue() ?? 0) + 1;
    }
}
