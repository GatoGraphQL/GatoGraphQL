<?php

declare(strict_types=1);

namespace PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PharIo\Version\Version;

/**
 * Remove "-dev" from the plugin version
 */
class ConvertVersionForProdInPluginMainFileReleaseWorker extends AbstractConvertVersionInPluginMainFileReleaseWorker
{
    public function work(Version $version): void
    {
        $requiredCurrentDevVersion = $this->versionUtils->getRequiredCurrentFormat($version);

        $prodVersion = $version->getVersionString();
        $replacements = [
            // WordPress plugin header
            '/Version:\s+[a-z0-9.-]+/' => 'Version: ' . $prodVersion,
            // Gato GraphQL plugin/extension version
            "/" . preg_quote('$pluginVersion') . " = '[a-z0-9.-]+';/" => "\$pluginVersion = '$prodVersion';",
            "/" . preg_quote('$extensionVersion') . " = '[a-z0-9.-]+';/" => "\$extensionVersion = '$prodVersion';",
            // Main Gato GraphQL plugin version constraint
            "/" . preg_quote('$mainPluginVersionConstraint') . " = '[0-9.^]+';/" => "\$mainPluginVersionConstraint = '$requiredCurrentDevVersion';",
        ];
        $this->fileContentReplacerSystem->replaceContentInFiles($this->getPluginMainFiles(), $replacements, true);
    }

    public function getDescription(Version $version): string
    {
        return 'Remove "-dev" from the plugin version in the plugin main file';
    }
}
