<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Json;

use PoP\PoP\Extensions\Symplify\MonorepoBuilder\ValueObject\Option;
use PoP\PoP\Extensions\Symplify\MonorepoBuilder\ValueObject\OptionValues;
use Symplify\PackageBuilder\Parameter\ParameterProvider;
use Symplify\SymplifyKernel\Exception\ShouldNotHappenException;

final class PluginConfigEntriesJsonProvider
{
    /**
     * @var array<array<mixed>>
     */
    private array $pluginConfigEntries = [];

    public function __construct(
        ParameterProvider $parameterProvider,
    ) {
        $this->pluginConfigEntries = $parameterProvider->provideArrayParameter(Option::PLUGIN_CONFIG_ENTRIES);
    }

    /**
     * @param string[] $extensionTypeFilter
     * @param string[] $extensionSlugFilter
     * @return array<array<string,string>>
     */
    public function providePluginConfigEntries(
        bool $scopedOnly = false,
        array $extensionTypeFilter = [],
        array $extensionSlugFilter = [],
    ): array {
        /**
         * Validate that all required entries have been provided.
         *
         * The version is mandatory, and can't use a default one,
         * as to allow a downstream monorepo to use the same script
         * and pass the "current" version when deploying to PROD
         * (i.e. after a new version has been produced for the
         * upstream monorepo).
         */
        $requiredEntries = [
            'path',
            'plugin_slug',
            'version',
            'main_file',
            // 'dist_repo_organization',
            // 'dist_repo_name',
            'rector_downgrade_config',
        ];
        /**
         * Validate that all scoping required entries have been provided
         */
        $scopingRequiredEntries = [
            'phpscoper_config',
            'rector_test_config',
        ];
        $pluginConfigEntries = [];
        $sourcePluginConfigEntries = $this->pluginConfigEntries;
        if ($scopedOnly) {
            $sourcePluginConfigEntries = array_filter(
                $sourcePluginConfigEntries,
                fn (array $entry) => isset($entry['scoping'])
            );
        }
        foreach ($sourcePluginConfigEntries as $entryConfig) {
            $unprovidedEntries = array_diff(
                $requiredEntries,
                array_keys((array) $entryConfig)
            );
            if ($unprovidedEntries !== []) {
                throw new ShouldNotHappenException(sprintf(
                    "The following entries must be provided for generating the plugin: '%s'",
                    implode("', '", $unprovidedEntries)
                ));
            }

            // If it is scoping, check that all required entries are provided
            if (isset($entryConfig['scoping'])) {
                $unprovidedScopingEntries = array_diff(
                    $scopingRequiredEntries,
                    array_keys((array) $entryConfig['scoping'])
                );
                if ($unprovidedScopingEntries !== []) {
                    throw new ShouldNotHappenException(sprintf(
                        "The following entries must be provided for scoping the plugin: '%s'",
                        implode("', '", $unprovidedScopingEntries)
                    ));
                }
            }

            // The .zip filename is the plugin slug + the version
            $entryConfig['zip_file'] ??= sprintf(
                '%s-%s',
                $entryConfig['plugin_slug'],
                $entryConfig['version']
            );

            // If it doesn't specify a branch, use "main" by default
            $entryConfig['dist_repo_branch'] ??= 'main';

            // If not provided, the "Publish to DIST repo" will not be executed
            $entryConfig['dist_repo_organization'] ??= '';
            $entryConfig['dist_repo_name'] ??= '';

            // Merge all rector configs as a string
            $entryConfig['additional_rector_before_configs'] = implode(' ', $entryConfig['additional_rector_before_configs'] ?? []);
            $entryConfig['additional_rector_after_configs'] = implode(' ', $entryConfig['additional_rector_after_configs'] ?? []);

            // Transfer the "replace" entries in composer.json, from dependency packages to the root package
            $entryConfig['is_bundle'] ??= false;
            $entryConfig['exclude_replace'] ??= '';

            // Gato GraphQL vs Gato Standalone plugins
            $entryConfig['is_standalone_plugin'] ??= false;

            // Hacks to be executed on the plugin
            $entryConfig['bashScripts'] ??= [];

            // Copy folder .wordpress-org to the DIST repo
            $entryConfig['include_folders_for_dist_repo'] ??= '';

            $pluginConfigEntries[] = $entryConfig;
        }

        /**
         * Allow to generate only bundles or extension in
         * GitHub Actions, by passing `--filter=extension`
         * or `--filter=bundle`.
         *
         * Make sure only allowed values are passed for filtering.
         */
        $extensionTypeFilter = array_filter(
            $extensionTypeFilter,
            fn (string $filterValue) => in_array($filterValue, [
                OptionValues::EXTENSION,
                OptionValues::BUNDLE,
                OptionValues::STANDALONE_PLUGIN,
            ])
        );
        if ($extensionTypeFilter !== []) {
            $filteredPluginConfigEntries = [];

            // Keep the standalone plugins?
            if (in_array(OptionValues::STANDALONE_PLUGIN, $extensionTypeFilter)) {
                $filteredPluginConfigEntries = array_merge(
                    $filteredPluginConfigEntries,
                    array_values(array_filter(
                        $pluginConfigEntries,
                        fn (array $entry) => $entry['is_standalone_plugin']
                    ))
                );
            }

            // Keep the bundles?
            if (in_array(OptionValues::BUNDLE, $extensionTypeFilter)) {
                $filteredPluginConfigEntries = array_merge(
                    $filteredPluginConfigEntries,
                    array_values(array_filter(
                        $pluginConfigEntries,
                        fn (array $entry) => !$entry['is_standalone_plugin'] && $entry['is_bundle']
                    ))
                );
            }

            // Keep the extensions?
            if (in_array(OptionValues::EXTENSION, $extensionTypeFilter)) {
                $filteredPluginConfigEntries = array_merge(
                    $filteredPluginConfigEntries,
                    array_values(array_filter(
                        $pluginConfigEntries,
                        fn (array $entry) => !$entry['is_standalone_plugin'] && !$entry['is_bundle']
                    ))
                );
            }

            $pluginConfigEntries = $filteredPluginConfigEntries;
        }

        // Remove any empty entry
        $extensionSlugFilter = array_values(array_filter($extensionSlugFilter));
        if ($extensionSlugFilter !== []) {
            $pluginConfigEntries = array_values(array_filter(
                $pluginConfigEntries,
                fn (array $entry) => in_array($entry['plugin_slug'], $extensionSlugFilter)
            ));
        }

        return $pluginConfigEntries;
    }
}
