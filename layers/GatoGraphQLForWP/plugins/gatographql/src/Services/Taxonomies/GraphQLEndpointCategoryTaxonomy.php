<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Taxonomies;

use GatoGraphQL\GatoGraphQL\Registries\CustomPostTypeRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\CustomPostTypeInterface;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLEndpointCustomPostTypeInterface;
use PoP\Root\Facades\Instances\InstanceManagerFacade;

class GraphQLEndpointCategoryTaxonomy extends AbstractCategory
{
    /** @var CustomPostTypeInterface[]|null */
    protected ?array $customPostTypes = null;

    private ?CustomPostTypeRegistryInterface $customPostTypeRegistry = null;

    final protected function getCustomPostTypeRegistry(): CustomPostTypeRegistryInterface
    {
        if ($this->customPostTypeRegistry === null) {
            /** @var CustomPostTypeRegistryInterface */
            $customPostTypeRegistry = InstanceManagerFacade::getInstance()->getInstance(CustomPostTypeRegistryInterface::class);
            $this->customPostTypeRegistry = $customPostTypeRegistry;
        }
        return $this->customPostTypeRegistry;
    }

    public function isServiceEnabled(): bool
    {
        if (!parent::isServiceEnabled()) {
            return false;
        }
        return $this->getCustomPostTypes() !== [];
    }

    public function getTaxonomy(): string
    {
        return $this->getTaxonomyNamespace() . '-endpoint-category';
    }

    public function getTaxonomyName(bool $titleCase = true): string
    {
        return $titleCase ? \__('GraphQL Endpoint Category', 'gatographql') : \__('GraphQL endpoint category', 'gatographql');
    }

    /**
     * @param bool $titleCase Indicate if the name must be title case (for starting a sentence) or, otherwise, lowercase
     */
    public function getTaxonomyPluralNames(bool $titleCase = true): string
    {
        return $titleCase ? \__('Endpoint Categories', 'gatographql') : \__('endpoint categories', 'gatographql');
    }

    public function showInMenu(): ?string
    {
        $menu = parent::showInMenu();
        if ($menu === null) {
            return null;
        }

        // Show if any of the attached CPTs is shown
        foreach ($this->getCustomPostTypes() as $customPostType) {
            if (
                $customPostType->isServiceEnabled()
                && $customPostType->showInMenu()
            ) {
                return $menu;
            }
        }
        return null;
    }

    /**
     * @return CustomPostTypeInterface[]
     */
    public function getCustomPostTypes(): array
    {
        if ($this->customPostTypes === null) {
            $customPostTypeServices = $this->getCustomPostTypeRegistry()->getCustomPostTypes();
            $endpointCustomPostTypeServices = array_values(array_filter(
                $customPostTypeServices,
                fn (CustomPostTypeInterface $customPostTypeService) => $customPostTypeService instanceof GraphQLEndpointCustomPostTypeInterface
            ));
            $this->customPostTypes = array_values(array_filter(
                $endpointCustomPostTypeServices,
                fn (CustomPostTypeInterface $customPostTypeService) => $customPostTypeService->isServiceEnabled()
            ));
        }
        return $this->customPostTypes;
    }
}
