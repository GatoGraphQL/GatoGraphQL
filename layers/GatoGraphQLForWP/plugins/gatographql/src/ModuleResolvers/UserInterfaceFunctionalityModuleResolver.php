<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\ContentProcessors\MarkdownContentParserInterface;
use GatoGraphQL\GatoGraphQL\Plugin;
use GatoGraphQL\GatoGraphQL\Registries\CustomPostTypeRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\CustomPostTypeInterface;

class UserInterfaceFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;
    use UserInterfaceFunctionalityModuleResolverTrait;

    public final const EXCERPT_AS_DESCRIPTION = Plugin::NAMESPACE . '\excerpt-as-description';
    public final const WELCOME_GUIDES = Plugin::NAMESPACE . '\welcome-guides';
    public final const SCHEMA_CONFIGURATION_ADDITIONAL_DOCUMENTATION = Plugin::NAMESPACE . '\schema-configuration-additional-documentation';
    public final const CUSTOM_ENDPOINT_OVERVIEW = Plugin::NAMESPACE . '\custom-endpoint-overview';
    public final const PERSISTED_QUERY_ENDPOINT_OVERVIEW = Plugin::NAMESPACE . '\persisted-query-endpoint-overview';

    /** @var CustomPostTypeInterface[] */
    protected ?array $enabledCustomPostTypeServices = null;

    private ?MarkdownContentParserInterface $markdownContentParser = null;
    private ?CustomPostTypeRegistryInterface $customPostTypeRegistry = null;

    final public function setMarkdownContentParser(MarkdownContentParserInterface $markdownContentParser): void
    {
        $this->markdownContentParser = $markdownContentParser;
    }
    final protected function getMarkdownContentParser(): MarkdownContentParserInterface
    {
        if ($this->markdownContentParser === null) {
            /** @var MarkdownContentParserInterface */
            $markdownContentParser = $this->instanceManager->getInstance(MarkdownContentParserInterface::class);
            $this->markdownContentParser = $markdownContentParser;
        }
        return $this->markdownContentParser;
    }
    final public function setCustomPostTypeRegistry(CustomPostTypeRegistryInterface $customPostTypeRegistry): void
    {
        $this->customPostTypeRegistry = $customPostTypeRegistry;
    }
    final protected function getCustomPostTypeRegistry(): CustomPostTypeRegistryInterface
    {
        if ($this->customPostTypeRegistry === null) {
            /** @var CustomPostTypeRegistryInterface */
            $customPostTypeRegistry = $this->instanceManager->getInstance(CustomPostTypeRegistryInterface::class);
            $this->customPostTypeRegistry = $customPostTypeRegistry;
        }
        return $this->customPostTypeRegistry;
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
            case self::EXCERPT_AS_DESCRIPTION:
                return $this->getEnabledCustomPostTypeServices() === [];
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
            self::EXCERPT_AS_DESCRIPTION => \__('Excerpt as Description', 'gatographql'),
            self::WELCOME_GUIDES => \__('Welcome Guides', 'gatographql'),
            self::SCHEMA_CONFIGURATION_ADDITIONAL_DOCUMENTATION => \__('Additional Gato GraphQL Documentation', 'gatographql'),
            self::CUSTOM_ENDPOINT_OVERVIEW => \__('Custom Endpoint Overview', 'gatographql'),
            self::PERSISTED_QUERY_ENDPOINT_OVERVIEW => \__('Persisted Query Endpoint Overview', 'gatographql'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::EXCERPT_AS_DESCRIPTION => \__('Provide a description of the different entities (Custom Endpoints, Persisted Queries, and others) through their excerpt', 'gatographql'),
            self::WELCOME_GUIDES => sprintf(
                \__('Display welcome guides which demonstrate how to use the plugin\'s different functionalities. <em>It requires WordPress version \'%s\' or above, or Gutenberg version \'%s\' or above</em>', 'gatographql'),
                '5.5',
                '8.2'
            ),
            self::SCHEMA_CONFIGURATION_ADDITIONAL_DOCUMENTATION => \__('Documentation on using the Gato GraphQL', 'gatographql'),
            self::CUSTOM_ENDPOINT_OVERVIEW => \__('Sidebar component displaying Properties for a Custom Endpoint', 'gatographql'),
            self::PERSISTED_QUERY_ENDPOINT_OVERVIEW => \__('Sidebar component displaying Properties for a Persisted Query Endpoint', 'gatographql'),
            default => parent::getDescription($module),
        };
    }

    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        return match ($module) {
            self::WELCOME_GUIDES
                => false,
            self::EXCERPT_AS_DESCRIPTION,
                => $this->getEnabledCustomPostTypeServices() === [] ? false : null,
            self::SCHEMA_CONFIGURATION_ADDITIONAL_DOCUMENTATION,
            self::CUSTOM_ENDPOINT_OVERVIEW,
            self::PERSISTED_QUERY_ENDPOINT_OVERVIEW
                => true,
            default
                => parent::isPredefinedEnabledOrDisabled($module),
        };
    }

    /**
     * @return CustomPostTypeInterface[]
     */
    protected function getEnabledCustomPostTypeServices(): array
    {
        if ($this->enabledCustomPostTypeServices === null) {
            $customPostTypeServices = $this->getCustomPostTypeRegistry()->getCustomPostTypes();
            return array_values(array_filter(
                $customPostTypeServices,
                fn (CustomPostTypeInterface $customPostTypeService) => $customPostTypeService->isServiceEnabled()
            ));
        }
        return $this->enabledCustomPostTypeServices;
    }
}
