<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Utils;

use PharIo\Version\Version;
use Symplify\MonorepoBuilder\Utils\VersionUtils as UpstreamVersionUtils;

/**
 * @see \Symplify\MonorepoBuilder\Utils
 */
final class VersionUtils
{
    public function __construct(
        private UpstreamVersionUtils $upstreamVersionUtils,
    ) {
    }

    public function getNextVersion(Version | string $version): string
    {
        $requiredNextFormat = $this->upstreamVersionUtils->getRequiredNextFormat($version);
        return substr(
            $requiredNextFormat,
            strlen('^')
        ) . '.0';
    }
}
