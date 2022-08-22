<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Json;

use PoP\PoP\Extensions\Symplify\MonorepoBuilder\ValueObject\Option;
use Symplify\PackageBuilder\Parameter\ParameterProvider;
use Symplify\SymplifyKernel\Exception\ShouldNotHappenException;

final class PluginConfigEntriesJsonProvider
{
    /**
     * @var array<string,string>
     */
    private array $pluginConfigEntries = [];

    public function __construct(
        ParameterProvider $parameterProvider,
    ) {
        $this->pluginConfigEntries = $parameterProvider->provideArrayParameter(Option::PLUGIN_CONFIG_ENTRIES);
    }

    /**
     * @return array<array<string,string>>
     */
    public function providePluginConfigEntries(bool $scopedOnly = false): array
    {
        /**
         * Validate that all required entries have been provided
         */
        $requiredEntries = [
            'path',
            'zip_file',
            'main_file',
            'dist_repo_organization',
            'dist_repo_name',
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

            // If it doens't specify a branch, use "master" by default
            $entryConfig['dist_repo_branch'] ??= 'master';

            // Merge all rector configs as a string
            $entryConfig['additional_rector_configs'] = implode(' ', $entryConfig['additional_rector_configs'] ?? []);

            // Automatically set the entries for conditional checks in GitHub Actions
            $entryConfig['scope'] = isset($entryConfig['scoping']);

            $pluginConfigEntries[] = $entryConfig;
        }

        return $pluginConfigEntries;
    }
}
