<?php

declare(strict_types=1);

namespace PoP\PoP\OnDemand\Symplify\MonorepoBuilder\Release\ReleaseWorker;

use PharIo\Version\Version;

/**
 * Point to GitHub's tagged image URLs in the compiled Markdown files
 */
class ConvertVersionForProdInPluginBlockCompiledMarkdownFilesReleaseWorker extends AbstractConvertVersionInPluginBlockCompiledMarkdownFilesReleaseWorker
{
    public function work(Version $version): void
    {
        $gitHubRepoDocsRootURL = $this->getGitHubRepoDocsRootURL();
        $replacements = [
            // Change the image src (pointing to GitHub) from master to the tag
            $gitHubRepoDocsRootURL . '/' . $this->branchName . '/' => $gitHubRepoDocsRootURL . '/' . $version->getVersionString() . '/',
        ];
        $this->fileContentReplacerSystem->replaceContentInFiles(
            $this->getPluginBlockCompiledMarkdownFiles(),
            $replacements,
            false
        );
    }

    public function getDescription(Version $version): string
    {
        return 'Point to GitHub\'s tagged image URLs in the compiled Markdown files in all blocks';
    }
}
