<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Json;

use PoP\PoP\Extensions\Symplify\MonorepoBuilder\ValueObject\Option;
use Symplify\PackageBuilder\Parameter\ParameterProvider;
use Symplify\SymplifyKernel\Exception\ShouldNotHappenException;

final class PluginConfigEntriesJsonProvider
{
    /**
     * @var array<string, string>
     */
    private array $pluginConfigEntries = [];

    public function __construct(
        ParameterProvider $parameterProvider
    ) {
        $this->pluginConfigEntries = $parameterProvider->provideArrayParameter(Option::PLUGIN_CONFIG_ENTRIES);
    }

    /**
     * @param string[] $fileListFilter
     * @return array<array<string,string>>
     */
    public function providePluginConfigEntries(array $fileListFilter = []): array
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
        ];
        $pluginConfigEntries = [];
        foreach ($this->pluginConfigEntries as $entryConfig) {
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

            // If it doens't specify a branch, use "master" by default
            $entryConfig['dist_repo_branch'] ??= 'master';

            // Merge all rector configs as a string
            $entryConfig['additional_rector_configs'] = implode(' ', $entryConfig['additional_rector_configs'] ?? []);

            $pluginConfigEntries[] = $entryConfig;
        }

        return $pluginConfigEntries;
    }
}
