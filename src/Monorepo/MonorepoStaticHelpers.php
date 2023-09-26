<?php

declare(strict_types=1);

namespace PoP\PoP\Monorepo;

class MonorepoStaticHelpers
{
    public static function getGitHubRepoDocsRootURL(): string
    {
        return sprintf(
            'https://raw.githubusercontent.com/%s/%s',
            MonorepoMetadata::GITHUB_REPO_OWNER,
            MonorepoMetadata::GITHUB_REPO_NAME
        );
    }
}
