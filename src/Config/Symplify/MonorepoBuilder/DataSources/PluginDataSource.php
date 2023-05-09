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
        $excludeJSBlockFilesPlaceholder = $this->getExcludeJSBlockFilesPlaceholder();
        return [
            // Gato GraphQL
            [
                'path' => 'layers/GatoGraphQLForWP/plugins/gato-graphql',
                'zip_file' => 'gato-graphql',
                'main_file' => 'gato-graphql.php',
                'exclude_files' => implode(' ', [
                    'dev-helpers/\*',
                    'docs/images/\*',
                    'docs-pro/images/\*',
                    sprintf($excludeJSBlockFilesPlaceholder, 'blocks'),
                    sprintf($excludeJSBlockFilesPlaceholder, 'blocks-pro'),
                    sprintf($excludeJSBlockFilesPlaceholder, 'editor-scripts'),
                    sprintf($excludeJSBlockFilesPlaceholder, 'packages'),
                ]),
                'dist_repo_organization' => 'GatoGraphQL',
                'dist_repo_name' => 'gato-graphql-dist',
                'additional_rector_configs' => [
                    $this->rootDir . '/config/rector/downgrade/gato-graphql/chained-rules/rector-cacheitem.php',
                    $this->rootDir . '/config/rector/downgrade/gato-graphql/chained-rules/rector-arrowfunction-mixedtype.php',
                    $this->rootDir . '/config/rector/downgrade/gato-graphql/chained-rules/rector-arrowfunction-uniontype.php',
                ],
                'rector_downgrade_config' => $this->rootDir . '/config/rector/downgrade/gato-graphql/rector.php',
                'scoping' => [
                    'phpscoper_config' => $this->rootDir . '/ci/scoping/scoper-gato-graphql.inc.php',
                    'rector_test_config' => $this->rootDir . '/ci/scoping/rector-test-scoping-gato-graphql.php',
                ],
                'bashScripts' => [
                    'after_downgrade_code' => 'ci/downgrade/after_downgrade_code.sh',
                ],
            ],
            // Gato GraphQL - Testing Schema <= To run integration tests
            [
                'path' => 'layers/GatoGraphQLForWP/plugins/testing-schema',
                'zip_file' => 'gato-graphql-testing-schema',
                'main_file' => 'gato-graphql-testing-schema.php',
                'dist_repo_organization' => 'GatoGraphQL',
                'dist_repo_name' => 'gato-graphql-testing-schema-dist',
                'rector_downgrade_config' => $this->rootDir . '/config/rector/downgrade/testing-schema/rector.php',
            ],
            // Gato GraphQL - Testing <= To run integration tests with InstaWP
            [
                'path' => 'layers/GatoGraphQLForWP/phpunit-plugins/gato-graphql-testing',
                'zip_file' => 'gato-graphql-testing',
                'main_file' => 'gato-graphql-testing.php',
                'dist_repo_organization' => 'GatoGraphQL',
                'dist_repo_name' => 'gato-graphql-testing-dist',
                'rector_downgrade_config' => $this->rootDir . '/config/rector/downgrade/testing/rector.php',
            ],
            // Gato GraphQL - Extension Demo
            // @todo Re-enable when the demo is actually complete
            // [
            //     'path' => 'layers/GatoGraphQLForWP/plugins/extension-demo',
            //     'zip_file' => 'gato-graphql-extension-demo',
            //     'main_file' => 'gato-graphql-extension-demo.php',
            //     'exclude_files' => 'docs/images/\*',
            //     'dist_repo_organization' => 'GatoGraphQL',
            //     'dist_repo_name' => 'gato-graphql-extension-demo-dist',
            //     'rector_downgrade_config' => $this->rootDir . '/config/rector/downgrade/extension-demo/rector.php',
            // ],
        ];
    }

    protected function getExcludeJSBlockFilesPlaceholder(): string
    {
        return '%1$s/*/docs/\* %1$s/*/src/\* %1$s/*/webpack.config.js';
    }
}
