<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Symplify\MonorepoBuilder;

class PluginConfig
{
    /**
     * @return array<array<mixed>
     */
    public function getPluginConfigEntries(string $dir): array
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
                    $dir . '/ci/downgrades/rector-downgrade-code-graphql-api-hacks-CacheItem.php',
                    $dir . '/ci/downgrades/rector-downgrade-code-graphql-api-hacks-ArrowFnMixedType.php',
                    $dir . '/ci/downgrades/rector-downgrade-code-graphql-api-hacks-ArrowFnUnionType.php',
                ],
                'rector_downgrade_config' => $dir . '/ci/downgrades/rector-downgrade-code-graphql-api.php',
                'scoping' => [
                    'phpscoper_config' => $dir . '/ci/scoping/scoper-graphql-api.inc.php',
                    'rector_test_config' => $dir . '/ci/scoping/rector-test-scoping-graphql-api.php',
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
                'rector_downgrade_config' => $dir . '/ci/downgrades/rector-downgrade-code-extension-demo.php',
            ],
        ];
    }
}
