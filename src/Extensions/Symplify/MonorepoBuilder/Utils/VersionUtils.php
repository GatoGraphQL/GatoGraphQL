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

    public function __construct(
        ParameterProvider $parameterProvider,
        private UpstreamVersionUtils $upstreamVersionUtils,
    ) {
        $this->packageAliasFormat = $parameterProvider->provideStringParameter(Option::PACKAGE_ALIAS_FORMAT);
    }

    public function getNextVersion(Version | string $version): string
    {
        $requiredNextFormat = $this->upstreamVersionUtils->getRequiredNextFormat($version);
        return substr(
            $requiredNextFormat,
            strlen('^')
        ) . '.0';
    }

    public function getNextDevVersion(Version | string $version): string
    {
        return $this->getNextVersion($version) . '-dev';
    }

    public function getCurrentAliasFormat(Version | string $version): string
    {
        $version = $this->normalizeVersion($version);

        /** @var Version $minor */
        $minor = $this->getCurrentMinorNumber($version);

        return str_replace(
            ['<major>', '<minor>'],
            [$version->getMajor()->getValue(), $minor],
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
        return (int) $version->getMinor()->getValue();
    }
}
