<?php

declare(strict_types=1);

namespace PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PharIo\Version\Version;
use PoP\PoP\Monorepo\MonorepoStaticHelpers;

/**
 * Point to GitHub's tagged image URLs in the compiled Markdown files
 */
class ConvertVersionForProdInPluginBlockCompiledMarkdownFilesReleaseWorker extends AbstractConvertVersionInPluginBlockCompiledMarkdownFilesReleaseWorker
{
    public function work(Version $version): void
    {
        $gitHubRepoDocsRootURL = MonorepoStaticHelpers::getGitHubRepoDocsRootURL();
        $replacements = [
            // Change the image src (pointing to GitHub) from master to the tag
            '#' . preg_quote($gitHubRepoDocsRootURL . '/' . $this->branchName . '/') . '#' => $gitHubRepoDocsRootURL . '/' . $version->getVersionString() . '/',
        ];
        $this->fileContentReplacerSystem->replaceContentInFiles($this->getPluginBlockCompiledMarkdownFiles(), $replacements);
    }

    public function getDescription(Version $version): string
    {
        return 'Point to GitHub\'s tagged image URLs in the compiled Markdown files in all blocks';
    }
}
