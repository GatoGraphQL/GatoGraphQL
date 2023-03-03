<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\PROPluginPseudoModules\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\AbstractFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\PerformanceFunctionalityModuleResolverTrait;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPIPRO\Constants\SettingsDefaultValues;

/**
 * The cache modules have different behavior depending on the environment:
 * - "development": visible, disabled by default
 * - "production": hidden, enabled by default
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
class PerformanceFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;
    use PerformanceFunctionalityModuleResolverTrait;

    public final const CACHE_CONTROL = 'placeholder:' . Plugin::NAMESPACE . '\cache-control';

    // /**
    //  * Setting options
    //  */
    // public final const OPTION_MAX_AGE = 'max-age';

    private ?MarkdownContentParserInterface $markdownContentParser = null;

    final public function setMarkdownContentParser(MarkdownContentParserInterface $markdownContentParser): void
    {
        $this->markdownContentParser = $markdownContentParser;
    }
    final protected function getMarkdownContentParser(): MarkdownContentParserInterface
    {
        /** @var MarkdownContentParserInterface */
        return $this->markdownContentParser ??= $this->instanceManager->getInstance(MarkdownContentParserInterface::class);
    }

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::CACHE_CONTROL,
        ];
    }

    /**
     * @return array<string[]> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     */
    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::CACHE_CONTROL:
                return [
                    [
                        SchemaConfigurationFunctionalityModuleResolver::SCHEMA_CONFIGURATION,
                    ],
                    [
                        EndpointFunctionalityModuleResolver::PERSISTED_QUERIES,
                    ],
                ];
        }
        return parent::getDependedModuleLists($module);
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::CACHE_CONTROL => \__('Cache Control [PRO]', 'graphql-api-pro'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::CACHE_CONTROL:
                return \__('Provide HTTP Caching for Persisted Queries, sending the Cache-Control header with a max-age value calculated from all fields in the query', 'graphql-api-pro');
        }
        return parent::getDescription($module);
    }

    // /**
    //  * Default value for an option set by the module
    //  */
    // public function getSettingsDefaultValue(string $module, string $option): mixed
    // {
    //     $defaultValues = [
    //         self::CACHE_CONTROL => [
    //             self::OPTION_MAX_AGE => SettingsDefaultValues::CACHE_CONTROL_MAX_AGE,
    //         ],
    //     ];
    //     return $defaultValues[$module][$option] ?? null;
    // }

    // /**
    //  * Array with the inputs to show as settings for the module
    //  *
    //  * @return array<array<string,mixed>> List of settings for the module, each entry is an array with property => value
    //  */
    // public function getSettings(string $module): array
    // {
    //     $moduleSettings = parent::getSettings($module);
    //     // Do the if one by one, so that the SELECT do not get evaluated unless needed
    //     if ($module === self::CACHE_CONTROL) {
    //         $option = self::OPTION_MAX_AGE;
    //         $moduleSettings[] = [
    //             Properties::INPUT => $option,
    //             Properties::NAME => $this->getSettingOptionName(
    //                 $module,
    //                 $option
    //             ),
    //             Properties::TITLE => \__('Default max-age', 'graphql-api-pro'),
    //             Properties::DESCRIPTION => \__('Default max-age value (in seconds) for the Cache-Control header, for all fields and directives in the schema', 'graphql-api-pro'),
    //             Properties::TYPE => Properties::TYPE_INT,
    //             Properties::MIN_NUMBER => 0,
    //         ];
    //     }
    //     return $moduleSettings;
    // }
}
