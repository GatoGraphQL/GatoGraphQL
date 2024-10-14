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
    public final const SCHEMA_CONFIGURATION_ADDITIONAL_DOCUMENTATION = Plugin::NAMESPACE . '\schema-configuration-additional-documentation';

    /** @var CustomPostTypeInterface[] */
    protected ?array $useExcerptAsDescriptionEnabledCustomPostTypeServices = null;

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
            self::SCHEMA_CONFIGURATION_ADDITIONAL_DOCUMENTATION,
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
            case self::SCHEMA_CONFIGURATION_ADDITIONAL_DOCUMENTATION:
                return [
                    [
                        SchemaConfigurationFunctionalityModuleResolver::SCHEMA_CONFIGURATION,
                    ],
                ];
        }
        return parent::getDependedModuleLists($module);
    }

    public function isHidden(string $module): bool
    {
        switch ($module) {
            case self::EXCERPT_AS_DESCRIPTION:
                return $this->getUseExcerptAsDescriptionEnabledCustomPostTypeServices() === [];
            case self::SCHEMA_CONFIGURATION_ADDITIONAL_DOCUMENTATION:
                return true;
        }
        return parent::isHidden($module);
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::EXCERPT_AS_DESCRIPTION => \__('Excerpt as Description', 'gatographql'),
            self::SCHEMA_CONFIGURATION_ADDITIONAL_DOCUMENTATION => \__('Additional Gato GraphQL Documentation', 'gatographql'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::EXCERPT_AS_DESCRIPTION => \__('Provide a description of the different entities (Custom Endpoints, Persisted Queries, and others) through their excerpt', 'gatographql'),
            self::SCHEMA_CONFIGURATION_ADDITIONAL_DOCUMENTATION => \__('Documentation on using the Gato GraphQL', 'gatographql'),
            default => parent::getDescription($module),
        };
    }

    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        return match ($module) {
            self::EXCERPT_AS_DESCRIPTION => $this->getUseExcerptAsDescriptionEnabledCustomPostTypeServices() === [] ? false : null,
            self::SCHEMA_CONFIGURATION_ADDITIONAL_DOCUMENTATION => true,
            default => parent::isPredefinedEnabledOrDisabled($module),
        };
    }

    /**
     * @return CustomPostTypeInterface[]
     */
    protected function getUseExcerptAsDescriptionEnabledCustomPostTypeServices(): array
    {
        if ($this->useExcerptAsDescriptionEnabledCustomPostTypeServices === null) {
            $customPostTypeServices = $this->getCustomPostTypeRegistry()->getCustomPostTypes();
            $this->useExcerptAsDescriptionEnabledCustomPostTypeServices = array_values(array_filter(
                $customPostTypeServices,
                fn (CustomPostTypeInterface $customPostTypeService) => $customPostTypeService->isServiceEnabled() && $customPostTypeService->useCustomPostExcerptAsDescription()
            ));
        }
        return $this->useExcerptAsDescriptionEnabledCustomPostTypeServices;
    }
}
