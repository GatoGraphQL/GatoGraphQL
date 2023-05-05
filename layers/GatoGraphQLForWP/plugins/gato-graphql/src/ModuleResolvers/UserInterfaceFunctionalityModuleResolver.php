<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\ContentProcessors\MarkdownContentParserInterface;
use GatoGraphQL\GatoGraphQL\Plugin;

class UserInterfaceFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;
    use UserInterfaceFunctionalityModuleResolverTrait;

    public final const EXCERPT_AS_DESCRIPTION = Plugin::NAMESPACE . '\excerpt-as-description';
    public final const WELCOME_GUIDES = Plugin::NAMESPACE . '\welcome-guides';
    public final const SCHEMA_CONFIGURATION_ADDITIONAL_DOCUMENTATION = Plugin::NAMESPACE . '\schema-configuration-additional-documentation';
    public final const CUSTOM_ENDPOINT_OVERVIEW = Plugin::NAMESPACE . '\custom-endpoint-overview';
    public final const PERSISTED_QUERY_ENDPOINT_OVERVIEW = Plugin::NAMESPACE . '\persisted-query-endpoint-overview';

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
            self::CUSTOM_ENDPOINT_OVERVIEW,
            self::PERSISTED_QUERY_ENDPOINT_OVERVIEW,
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
                        EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS,
                        EndpointFunctionalityModuleResolver::PERSISTED_QUERIES,
                    ]
                ];
            case self::SCHEMA_CONFIGURATION_ADDITIONAL_DOCUMENTATION:
                return [
                    [
                        SchemaConfigurationFunctionalityModuleResolver::SCHEMA_CONFIGURATION,
                    ],
                ];
            case self::CUSTOM_ENDPOINT_OVERVIEW:
                return [
                    [
                        EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS,
                    ],
                ];
            case self::PERSISTED_QUERY_ENDPOINT_OVERVIEW:
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
            case self::CUSTOM_ENDPOINT_OVERVIEW:
            case self::PERSISTED_QUERY_ENDPOINT_OVERVIEW:
                return true;
        }
        return parent::isHidden($module);
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::EXCERPT_AS_DESCRIPTION => \__('Excerpt as Description', 'gato-graphql'),
            self::WELCOME_GUIDES => \__('Welcome Guides', 'gato-graphql'),
            self::SCHEMA_CONFIGURATION_ADDITIONAL_DOCUMENTATION => \__('Additional Gato GraphQL Documentation', 'gato-graphql'),
            self::CUSTOM_ENDPOINT_OVERVIEW => \__('Custom Endpoint Overview', 'gato-graphql'),
            self::PERSISTED_QUERY_ENDPOINT_OVERVIEW => \__('Persisted Query Endpoint Overview', 'gato-graphql'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::EXCERPT_AS_DESCRIPTION => \__('Provide a description of the different entities (Custom Endpoints, Persisted Queries, and others) through their excerpt', 'gato-graphql'),
            self::WELCOME_GUIDES => sprintf(
                \__('Display welcome guides which demonstrate how to use the plugin\'s different functionalities. <em>It requires WordPress version \'%s\' or above, or Gutenberg version \'%s\' or above</em>', 'gato-graphql'),
                '5.5',
                '8.2'
            ),
            self::SCHEMA_CONFIGURATION_ADDITIONAL_DOCUMENTATION => \__('Documentation on using the Gato GraphQL', 'gato-graphql'),
            self::CUSTOM_ENDPOINT_OVERVIEW => \__('Sidebar component displaying Properties for a Custom Endpoint', 'gato-graphql'),
            self::PERSISTED_QUERY_ENDPOINT_OVERVIEW => \__('Sidebar component displaying Properties for a Persisted Query Endpoint', 'gato-graphql'),
            default => parent::getDescription($module),
        };
    }

    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        return match ($module) {
            self::WELCOME_GUIDES
                => false,
            self::SCHEMA_CONFIGURATION_ADDITIONAL_DOCUMENTATION,
            self::CUSTOM_ENDPOINT_OVERVIEW,
            self::PERSISTED_QUERY_ENDPOINT_OVERVIEW
                => true,
            default
                => parent::isPredefinedEnabledOrDisabled($module),
        };
    }
}
