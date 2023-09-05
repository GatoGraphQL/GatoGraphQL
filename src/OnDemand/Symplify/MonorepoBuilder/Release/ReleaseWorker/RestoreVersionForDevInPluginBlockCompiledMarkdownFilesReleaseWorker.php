<?php

declare(strict_types=1);

namespace PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PharIo\Version\Version;
use PoP\PoP\Monorepo\MonorepoStaticHelpers;

/**
 * Point to GitHub's master image URLs in the compiled Markdown files
 */
class RestoreVersionForDevInPluginBlockCompiledMarkdownFilesReleaseWorker extends AbstractConvertVersionInPluginBlockCompiledMarkdownFilesReleaseWorker
{
    public function work(Version $version): void
    {
        $gitHubRepoDocsRootURL = MonorepoStaticHelpers::getGitHubRepoDocsRootURL();
        $replacements = [
            // Change the image src (pointing to GitHub) from the tag back to master
            '#' . preg_quote($gitHubRepoDocsRootURL . '/' . $version->getVersionString() . '/') . '#' => $gitHubRepoDocsRootURL . '/master/',
        ];
        $this->fileContentReplacerSystem->replaceContentInFiles($this->pluginBlockCompiledMarkdownFiles, $replacements);
    }

    public function getDescription(Version $version): string
    {
        return 'Point to GitHub\'s master image URLs in the compiled Markdown files in all blocks';
    }
}
