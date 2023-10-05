<?php

declare(strict_types=1);

namespace PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PharIo\Version\Version;

/**
 * Have the "Stable tag" point to the PROD version in the plugin's readme.txt file
 */
class ConvertStableTagVersionForProdInPluginReadmeFileReleaseWorker extends AbstractConvertStableTagVersionInPluginReadmeFileReleaseWorker
{
    public function work(Version $version): void
    {
        $prodVersion = $version->getVersionString();
        $replacements = [
            '/Stable tag:\s+[a-z0-9.-]+/' => 'Stable tag: ' . $prodVersion,
        ];
        $this->fileContentReplacerSystem->replaceContentInFiles($this->getPluginReadmeFiles(), $replacements, true);
    }

    public function getDescription(Version $version): string
    {
        return 'Have the "Stable tag" point to the PROD version in the plugin\'s readme.txt file';
    }
}
