<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\HybridServices\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\HybridServices\ModuleResolvers\ModuleResolverTrait;
use GraphQLAPI\GraphQLAPI\Services\ModuleTypeResolvers\ModuleTypeResolver;

class UserInterfaceFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;

    public const EXCERPT_AS_DESCRIPTION = Plugin::NAMESPACE . '\excerpt-as-description';
    public const LOW_LEVEL_PERSISTED_QUERY_EDITING = Plugin::NAMESPACE . '\low-level-persisted-query-editing';
    public const WELCOME_GUIDES = Plugin::NAMESPACE . '\welcome-guides';

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::LOW_LEVEL_PERSISTED_QUERY_EDITING,
            self::EXCERPT_AS_DESCRIPTION,
            self::WELCOME_GUIDES,
        ];
    }

    /**
     * Enable to customize a specific UI for the module
     */
    public function getModuleType(string $module): string
    {
        return ModuleTypeResolver::USER_INTERFACE;
    }

    /**
     * @return array<array> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     */
    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::LOW_LEVEL_PERSISTED_QUERY_EDITING:
                return [
                    [
                        EndpointFunctionalityModuleResolver::PERSISTED_QUERIES,
                    ],
                ];
            case self::EXCERPT_AS_DESCRIPTION:
                return [];
            case self::WELCOME_GUIDES:
                return [
                    [
                        EndpointFunctionalityModuleResolver::PERSISTED_QUERIES,
                        EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS,
                    ],
                ];
        }
        return parent::getDependedModuleLists($module);
    }

    public function areRequirementsSatisfied(string $module): bool
    {
        switch ($module) {
            case self::WELCOME_GUIDES:
                /**
                 * WordPress 5.5 or above, or Gutenberg 8.2 or above
                 */
                return
                    \is_wp_version_compatible('5.5') ||
                    (
                        defined('GUTENBERG_VERSION') &&
                        \version_compare(constant('GUTENBERG_VERSION'), '8.2', '>=')
                    );
        }
        return parent::areRequirementsSatisfied($module);
    }

    public function isHidden(string $module): bool
    {
        switch ($module) {
            case self::WELCOME_GUIDES:
                return true;
        }
        return parent::isHidden($module);
    }

    public function getName(string $module): string
    {
        $names = [
            self::EXCERPT_AS_DESCRIPTION => \__('Excerpt as Description', 'graphql-api'),
            self::LOW_LEVEL_PERSISTED_QUERY_EDITING => \__('Low-Level Persisted Query Editing', 'graphql-api'),
            self::WELCOME_GUIDES => \__('Welcome Guides', 'graphql-api'),
        ];
        return $names[$module] ?? $module;
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::EXCERPT_AS_DESCRIPTION:
                return \__('Provide a description of the different entities (Custom Endpoints, Persisted Queries, and others) through their excerpt', 'graphql-api');
            case self::LOW_LEVEL_PERSISTED_QUERY_EDITING:
                return \__('Have access to directives to be applied to the schema when editing persisted queries', 'graphql-api');
            case self::WELCOME_GUIDES:
                return sprintf(
                    \__('Display welcome guides which demonstrate how to use the plugin\'s different functionalities. <em>It requires WordPress version \'%s\' or above, or Gutenberg version \'%s\' or above</em>', 'graphql-api'),
                    '5.5',
                    '8.2'
                );
        }
        return parent::getDescription($module);
    }

    public function isEnabledByDefault(string $module): bool
    {
        switch ($module) {
            case self::LOW_LEVEL_PERSISTED_QUERY_EDITING:
            case self::WELCOME_GUIDES:
                return false;
        }
        return parent::isEnabledByDefault($module);
    }
}
