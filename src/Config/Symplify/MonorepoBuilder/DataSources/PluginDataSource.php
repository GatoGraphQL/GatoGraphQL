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
                'path' => 'layers/GatoGraphQLForWP/plugins/gatographql',
                'plugin_slug' => 'gatographql',
                'main_file' => 'gatographql.php',
                'exclude_files' => implode(' ', [
                    '.wordpress-org/\*',
                    'dev-helpers/\*',
                    'docs/images/\*',
                    'extensions/*/docs/images/\*',
                    sprintf($excludeJSBlockFilesPlaceholder, 'blocks'),
                    sprintf($excludeJSBlockFilesPlaceholder, 'editor-scripts'),
                    sprintf($excludeJSBlockFilesPlaceholder, 'packages'),
                ]),
                'dist_repo_organization' => 'GatoGraphQL',
                'dist_repo_name' => 'gatographql-dist',
                'additional_rector_configs' => [
                    $this->rootDir . '/config/rector/downgrade/gatographql/chained-rules/rector-arrowfunction-mixedtype.php',
                    $this->rootDir . '/config/rector/downgrade/gatographql/chained-rules/rector-arrowfunction-uniontype.php',
                ],
                'rector_downgrade_config' => $this->rootDir . '/config/rector/downgrade/gatographql/rector.php',
                'scoping' => [
                    'phpscoper_config' => $this->rootDir . '/ci/scoping/scoper-gatographql.inc.php',
                    'rector_test_config' => $this->rootDir . '/ci/scoping/rector-test-scoping-gatographql.php',
                ],
                'bashScripts' => [
                    'after_downgrade_code' => 'ci/downgrade/after_downgrade_code.sh',
                ],
            ],

            // Gato GraphQL - Testing Schema <= To run integration tests
            [
                'path' => 'layers/GatoGraphQLForWP/plugins/testing-schema',
                'plugin_slug' => 'gatographql-testing-schema',
                'main_file' => 'gatographql-testing-schema.php',
                'exclude_files' => implode(' ', [
                    sprintf($excludeJSBlockFilesPlaceholder, 'blocks'),
                ]),
                'dist_repo_organization' => 'GatoGraphQL',
                'dist_repo_name' => 'gatographql-testing-schema-dist',
                'rector_downgrade_config' => $this->rootDir . '/config/rector/downgrade/testing-schema/rector.php',
            ],

            // Gato GraphQL - Testing <= To run integration tests with InstaWP
            [
                'path' => 'layers/GatoGraphQLForWP/phpunit-plugins/gatographql-testing',
                'plugin_slug' => 'gatographql-testing',
                'main_file' => 'gatographql-testing.php',
                'dist_repo_organization' => 'GatoGraphQL',
                'dist_repo_name' => 'gatographql-testing-dist',
                'rector_downgrade_config' => $this->rootDir . '/config/rector/downgrade/testing/rector.php',
            ],

            // Gato GraphQL - Extension Demo
            // @todo Re-enable when the demo is actually complete
            // [
            //     'path' => 'layers/GatoGraphQLForWP/plugins/extension-demo',
            //     'plugin_slug' => 'gatographql-extension-demo',
            //     'main_file' => 'gatographql-extension-demo.php',
            //     'exclude_files' => 'docs/images/\*',
            //     'dist_repo_organization' => 'GatoGraphQL',
            //     'dist_repo_name' => 'gatographql-extension-demo-dist',
            //     'rector_downgrade_config' => $this->rootDir . '/config/rector/downgrade/extension-demo/rector.php',
            // ],
        ];
    }

    protected function getExcludeJSBlockFilesPlaceholder(): string
    {
        return implode(' ', [
            '%1$s/*/docs/\*',
            '%1$s/*/graphql-documents/\*',
            '%1$s/*/src/\*',
            '%1$s/*/package-lock.json',
            '%1$s/*/package.json',
            '%1$s/*/webpack.config.js',
        ]);
    }
}
