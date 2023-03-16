<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface;
use GraphQLAPI\GraphQLAPI\Plugin;

class UserInterfaceFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;
    use UserInterfaceFunctionalityModuleResolverTrait;

    public final const EXCERPT_AS_DESCRIPTION = Plugin::NAMESPACE . '\excerpt-as-description';
    public final const WELCOME_GUIDES = Plugin::NAMESPACE . '\welcome-guides';
    public final const SCHEMA_CONFIGURATION_ADDITIONAL_DOCUMENTATION = Plugin::NAMESPACE . '\schema-configuration-additional-documentation';
    public final const CUSTOM_ENDPOINT_PROPERTIES = Plugin::NAMESPACE . '\custom-endpoint-properties';
    public final const PERSISTED_QUERY_ENDPOINT_PROPERTIES = Plugin::NAMESPACE . '\persisted-query-endpoint-properties';

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
            self::EXCERPT_AS_DESCRIPTION,
            self::WELCOME_GUIDES,
            self::SCHEMA_CONFIGURATION_ADDITIONAL_DOCUMENTATION,
            self::CUSTOM_ENDPOINT_PROPERTIES,
            self::PERSISTED_QUERY_ENDPOINT_PROPERTIES,
        ];
    }

    /**
     * @return array<string[]> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     */
    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::EXCERPT_AS_DESCRIPTION:
                return [];
            case self::WELCOME_GUIDES:
                return [
                    [
                        EndpointFunctionalityModuleResolver::PERSISTED_QUERIES,
                        EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS,
                    ]
                ];
            case self::SCHEMA_CONFIGURATION_ADDITIONAL_DOCUMENTATION:
                return [
                    [
                        SchemaConfigurationFunctionalityModuleResolver::SCHEMA_CONFIGURATION,
                    ],
                ];
            case self::CUSTOM_ENDPOINT_PROPERTIES:
                return [
                    [
                        EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS,
                    ],
                ];
            case self::PERSISTED_QUERY_ENDPOINT_PROPERTIES:
                return [
                    [
                        EndpointFunctionalityModuleResolver::PERSISTED_QUERIES,
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
            case self::SCHEMA_CONFIGURATION_ADDITIONAL_DOCUMENTATION:
            case self::CUSTOM_ENDPOINT_PROPERTIES:
            case self::PERSISTED_QUERY_ENDPOINT_PROPERTIES:
                return true;
        }
        return parent::isHidden($module);
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::EXCERPT_AS_DESCRIPTION => \__('Excerpt as Description', 'graphql-api'),
            self::WELCOME_GUIDES => \__('Welcome Guides', 'graphql-api'),
            self::SCHEMA_CONFIGURATION_ADDITIONAL_DOCUMENTATION => \__('Additional GraphQL API Documentation', 'graphql-api'),
            self::CUSTOM_ENDPOINT_PROPERTIES => \__('Custom Endpoint Properties', 'graphql-api'),
            self::PERSISTED_QUERY_ENDPOINT_PROPERTIES => \__('Persisted Query Endpoint Properties', 'graphql-api'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::EXCERPT_AS_DESCRIPTION => \__('Provide a description of the different entities (Custom Endpoints, Persisted Queries, and others) through their excerpt', 'graphql-api'),
            self::WELCOME_GUIDES => sprintf(
                \__('Display welcome guides which demonstrate how to use the plugin\'s different functionalities. <em>It requires WordPress version \'%s\' or above, or Gutenberg version \'%s\' or above</em>', 'graphql-api'),
                '5.5',
                '8.2'
            ),
            self::SCHEMA_CONFIGURATION_ADDITIONAL_DOCUMENTATION => \__('Documentation on using the GraphQL API', 'graphql-api'),
            self::CUSTOM_ENDPOINT_PROPERTIES => \__('Sidebar component displaying Properties for a Custom Endpoint', 'graphql-api'),
            self::PERSISTED_QUERY_ENDPOINT_PROPERTIES => \__('Sidebar component displaying Properties for a Persisted Query Endpoint', 'graphql-api'),
            default => parent::getDescription($module),
        };
    }

    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        return match ($module) {
            self::WELCOME_GUIDES
                => false,
            self::SCHEMA_CONFIGURATION_ADDITIONAL_DOCUMENTATION,
            self::CUSTOM_ENDPOINT_PROPERTIES,
            self::PERSISTED_QUERY_ENDPOINT_PROPERTIES
                => true,
            default
                => parent::isPredefinedEnabledOrDisabled($module),
        };
    }
}
