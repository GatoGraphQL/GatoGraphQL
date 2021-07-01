<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources;

class PluginDataSource
{
    function __construct(protected string $dir)
    {        
    }

    /**
     * @return array<array<mixed>
     */
    public function getPluginConfigEntries(): array
    {
        return [
            // GraphQL API for WordPress
            [
                'path' => 'layers/GraphQLAPIForWP/plugins/graphql-api-for-wp',
                'zip_file' => 'graphql-api.zip',
                'main_file' => 'graphql-api.php',
                'exclude_files' => 'dev-helpers/\* docs/images/\*',
                'dist_repo_organization' => 'GraphQLAPI',
                'dist_repo_name' => 'graphql-api-for-wp-dist',
                'additional_rector_configs' => [
                    $this->dir . '/ci/downgrades/rector-downgrade-code-graphql-api-hacks-CacheItem.php',
                    $this->dir . '/ci/downgrades/rector-downgrade-code-graphql-api-hacks-ArrowFnMixedType.php',
                    $this->dir . '/ci/downgrades/rector-downgrade-code-graphql-api-hacks-ArrowFnUnionType.php',
                ],
                'rector_downgrade_config' => $this->dir . '/ci/downgrades/rector-downgrade-code-graphql-api.php',
                'scoping' => [
                    'phpscoper_config' => $this->dir . '/ci/scoping/scoper-graphql-api.inc.php',
                    'rector_test_config' => $this->dir . '/ci/scoping/rector-test-scoping-graphql-api.php',
                ],
            ],
            // GraphQL API - Extension Demo
            [
                'path' => 'layers/GraphQLAPIForWP/plugins/extension-demo',
                'zip_file' => 'graphql-api-extension-demo.zip',
                'main_file' => 'graphql-api-extension-demo.php',
                'exclude_files' => 'docs/images/\*',
                'dist_repo_organization' => 'GraphQLAPI',
                'dist_repo_name' => 'extension-demo-dist',
                'rector_downgrade_config' => $this->dir . '/ci/downgrades/rector-downgrade-code-extension-demo.php',
            ],
        ];
    }
}
