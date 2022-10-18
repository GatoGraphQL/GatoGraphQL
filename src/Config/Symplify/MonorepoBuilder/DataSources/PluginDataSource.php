<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources;

class PluginDataSource
{
    public function __construct(protected string $rootDir)
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
                'zip_file' => 'graphql-api',
                'main_file' => 'graphql-api.php',
                'exclude_files' => 'dev-helpers/\* docs/images/\*',
                'dist_repo_organization' => 'GraphQLAPI',
                'dist_repo_name' => 'graphql-api-for-wp-dist',
                'additional_rector_configs' => [
                    $this->rootDir . '/config/rector/downgrade/graphql-api/chained-rules/rector-cacheitem.php',
                    $this->rootDir . '/config/rector/downgrade/graphql-api/chained-rules/rector-arrowfunction-mixedtype.php',
                    $this->rootDir . '/config/rector/downgrade/graphql-api/chained-rules/rector-arrowfunction-uniontype.php',
                ],
                'rector_downgrade_config' => $this->rootDir . '/config/rector/downgrade/graphql-api/rector.php',
                'scoping' => [
                    'phpscoper_config' => $this->rootDir . '/ci/scoping/scoper-graphql-api.inc.php',
                    'rector_test_config' => $this->rootDir . '/ci/scoping/rector-test-scoping-graphql-api.php',
                ],
                'bashScripts' => [
                    'after_downgrade_code' => 'ci/downgrade/after_downgrade_code.sh',
                ],
            ],
            // GraphQL API - Testing <= To run integration tests with InstaWP
            [
                'path' => 'layers/GraphQLAPIForWP/phpunit-plugins/graphql-api-for-wp-testing',
                'zip_file' => 'graphql-api-testing',
                'main_file' => 'graphql-api-testing.php',
                'dist_repo_organization' => 'GraphQLAPI',
                'dist_repo_name' => 'graphql-api-testing-dist',
                'rector_downgrade_config' => $this->rootDir . '/config/rector/downgrade/testing/rector.php',
            ],
            // GraphQL API - Extension Demo
            // @todo Re-enable when the demo is actually complete
            // [
            //     'path' => 'layers/GraphQLAPIForWP/plugins/extension-demo',
            //     'zip_file' => 'graphql-api-extension-demo',
            //     'main_file' => 'graphql-api-extension-demo.php',
            //     'exclude_files' => 'docs/images/\*',
            //     'dist_repo_organization' => 'GraphQLAPI',
            //     'dist_repo_name' => 'graphql-api-extension-demo-dist',
            //     'rector_downgrade_config' => $this->rootDir . '/config/rector/downgrade/extension-demo/rector.php',
            // ],
        ];
    }
}
