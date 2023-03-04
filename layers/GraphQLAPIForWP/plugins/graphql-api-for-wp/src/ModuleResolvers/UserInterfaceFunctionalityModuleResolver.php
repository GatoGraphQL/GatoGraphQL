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
    public final const SCHEMA_CONFIGURATION_ADDITIONAL_DOCUMENTATION_PRO = Plugin::NAMESPACE . '\schema-configuration-additional-documentation-pro';

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
            self::SCHEMA_CONFIGURATION_ADDITIONAL_DOCUMENTATION_PRO,
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
            case self::SCHEMA_CONFIGURATION_ADDITIONAL_DOCUMENTATION_PRO:
                return [
                    [
                        SchemaConfigurationFunctionalityModuleResolver::SCHEMA_CONFIGURATION,
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
            case self::SCHEMA_CONFIGURATION_ADDITIONAL_DOCUMENTATION_PRO:
                return true;
        }
        return parent::isHidden($module);
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::EXCERPT_AS_DESCRIPTION => \__('Excerpt as Description', 'graphql-api'),
            self::WELCOME_GUIDES => \__('Welcome Guides', 'graphql-api'),
            self::SCHEMA_CONFIGURATION_ADDITIONAL_DOCUMENTATION => \__('Additional Documentation', 'graphql-api'),
            self::SCHEMA_CONFIGURATION_ADDITIONAL_DOCUMENTATION_PRO => \__('Additional Documentation', 'graphql-api'),
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
            self::SCHEMA_CONFIGURATION_ADDITIONAL_DOCUMENTATION_PRO => \__('Documentation on using the GraphQL API PRO', 'graphql-api'),
            default => parent::getDescription($module),
        };
    }

    public function isEnabledByDefault(string $module): bool
    {
        switch ($module) {
            case self::WELCOME_GUIDES:
                return false;
        }
        return parent::isEnabledByDefault($module);
    }
}
