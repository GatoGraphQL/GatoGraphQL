<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources;

use PoP\PoP\Monorepo\MonorepoMetadata;

class PluginDataSource
{
    public function __construct(protected string $rootDir)
    {
    }

    public function getRootDir(): string
    {
        return $this->rootDir;
    }

    /**
     * @return array<array<mixed>>
     */
    public function getPluginConfigEntries(): array
    {
        $excludeJSBlockFilesPlaceholder = $this->getExcludeJSBlockFilesPlaceholder();
        $pluginConfigEntries = [
            // Gato GraphQL
            [
                'path' => 'layers/GatoGraphQLForWP/plugins/gatographql',
                'plugin_slug' => 'gatographql',
                'main_file' => 'gatographql.php',
                'exclude_files' => implode(' ', [
                    '.wordpress-org/\*',
                    'dev-helpers/\*',
                    'block-config/\*',
                    'block-helpers/\*',
                    'docs/images/\*',
                    'extensions/*/docs/images/\*',
                    sprintf($excludeJSBlockFilesPlaceholder, 'blocks'),
                    sprintf($excludeJSBlockFilesPlaceholder, 'editor-scripts'),
                    sprintf($excludeJSBlockFilesPlaceholder, 'packages'),
                ]),
                'dist_repo_organization' => 'GatoGraphQLForWordPress',
                'dist_repo_name' => 'gatographql-dist',
                'additional_rector_before_configs' => [
                    $this->rootDir . '/config/rector/downgrade/plugins/gatographql/chained-rules/rector-change-if-or-return-earlyreturn.php',
                ],
                'additional_rector_after_configs' => [
                    $this->rootDir . '/config/rector/downgrade/plugins/gatographql/chained-rules/rector-arrowfunction-mixedtype.php',
                    $this->rootDir . '/config/rector/downgrade/plugins/gatographql/chained-rules/rector-arrowfunction-uniontype.php',
                ],
                'rector_downgrade_config' => $this->rootDir . '/config/rector/downgrade/plugins/gatographql/rector.php',
                'scoping' => [
                    'phpscoper_config' => $this->rootDir . '/ci/scoping/scoper-gatographql.inc.php',
                    'rector_test_config' => $this->rootDir . '/ci/scoping/rector-test-scoping-gatographql.php',
                ],
                'bashScripts' => [
                    'before_downgrade_code' => 'ci/downgrade/before_downgrade_code.sh',
                    'after_downgrade_code' => 'ci/downgrade/after_downgrade_code.sh',
                ],
                'include_folders_for_dist_repo' => implode(' ', [
                    // Copy the GitHub Actions workflow to deploy plugin to SVN
                    '.github',
                    // Copy all assets for the plugin directory
                    '.wordpress-org',
                ]),
            ],

            // Gato GraphQL - Testing Schema <= To run integration tests
            [
                'path' => 'layers/GatoGraphQLForWP/plugins/testing-schema',
                'plugin_slug' => 'gatographql-testing-schema',
                'main_file' => 'gatographql-testing-schema.php',
                'exclude_files' => implode(' ', [
                    sprintf($excludeJSBlockFilesPlaceholder, 'blocks'),
                ]),
                'dist_repo_organization' => 'GatoGraphQLForWordPress',
                'dist_repo_name' => 'gatographql-testing-schema-dist',
                'rector_downgrade_config' => $this->rootDir . '/config/rector/downgrade/plugins/testing-schema/rector.php',
            ],

            // Gato GraphQL - Testing <= To run integration tests with InstaWP
            [
                'path' => 'layers/GatoGraphQLForWP/phpunit-plugins/gatographql-testing',
                'plugin_slug' => 'gatographql-testing',
                'main_file' => 'gatographql-testing.php',
                'dist_repo_organization' => 'GatoGraphQLForWordPress',
                'dist_repo_name' => 'gatographql-testing-dist',
                'rector_downgrade_config' => $this->rootDir . '/config/rector/downgrade/phpunit-plugins/testing/rector.php',
            ],
        ];

        foreach ($pluginConfigEntries as &$pluginConfigEntry) {
            $pluginConfigEntry['version'] = MonorepoMetadata::VERSION;
            $pluginConfigEntry['dist_repo_branch'] = MonorepoMetadata::GIT_BASE_BRANCH;
        }

        return $pluginConfigEntries;
    }

    protected function getExcludeJSBlockFilesPlaceholder(): string
    {
        return implode(' ', [
            /**
             * Notice that this line also targets build/docs.
             * To avoid those files being excluded, in webpack.config.js
             * those docs are treated as @componentDocs
             */
            '%1$s/*/docs/\*',
            '%1$s/*/graphql-documents/\*',
            '%1$s/*/src/\*',
            '%1$s/*/package-lock.json',
            '%1$s/*/package.json',
            '%1$s/*/webpack.config.js',
        ]);
    }
}
