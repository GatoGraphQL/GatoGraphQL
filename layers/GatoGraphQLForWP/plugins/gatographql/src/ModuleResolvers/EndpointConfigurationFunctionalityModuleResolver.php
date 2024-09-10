<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\ContentProcessors\MarkdownContentParserInterface;
use GatoGraphQL\GatoGraphQL\Plugin;
use GatoGraphQL\GatoGraphQL\Registries\CustomPostTypeRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\CustomPostTypeInterface;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLEndpointCustomPostTypeInterface;

class EndpointConfigurationFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;
    use EndpointConfigurationFunctionalityModuleResolverTrait;

    public final const API_HIERARCHY = Plugin::NAMESPACE . '\api-hierarchy';

    /** @var GraphQLEndpointCustomPostTypeInterface[] */
    protected ?array $enabledHierarchicalEndpointCustomPostTypeServices = null;

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
            self::API_HIERARCHY,
        ];
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::API_HIERARCHY => \__('API Hierarchy', 'gatographql'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::API_HIERARCHY => \__('Create a hierarchy of API endpoints extending from other endpoints, and inheriting their properties', 'gatographql'),
            default => parent::getDescription($module),
        };
    }

    /**
     * If there are no endpoint CPTs enabled, the API Hierarchy
     * module is disabled
     */
    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        if ($module === self::API_HIERARCHY
            && $this->getEnabledHierarchicalEndpointCustomPostTypeServices() === []
        ) {
            return false;
        }
        return parent::isPredefinedEnabledOrDisabled($module);
    }

    /**
     * @return GraphQLEndpointCustomPostTypeInterface[]
     */
    protected function getEnabledHierarchicalEndpointCustomPostTypeServices(): array
    {
        if ($this->enabledHierarchicalEndpointCustomPostTypeServices === null) {
            $customPostTypeServices = $this->getCustomPostTypeRegistry()->getCustomPostTypes();
            $endpointCustomPostTypeServices = array_values(array_filter(
                $customPostTypeServices,
                fn (CustomPostTypeInterface $customPostTypeService) => $customPostTypeService instanceof GraphQLEndpointCustomPostTypeInterface
            ));
            $this->enabledHierarchicalEndpointCustomPostTypeServices = array_values(array_filter(
                $endpointCustomPostTypeServices,
                fn (GraphQLEndpointCustomPostTypeInterface $graphQLEndpointCustomPostTypeService) => $graphQLEndpointCustomPostTypeService->isServiceEnabled() && $graphQLEndpointCustomPostTypeService->isHierarchical()
            ));
        }
        return $this->enabledHierarchicalEndpointCustomPostTypeServices;
    }
}
