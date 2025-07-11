<?php

declare(strict_types=1);

namespace PoPCMSSchema\Taxonomies\ObjectTypeResolverPickers;

use PoPCMSSchema\Taxonomies\Module;
use PoPCMSSchema\Taxonomies\ModuleConfiguration;
use PoPCMSSchema\Taxonomies\Registries\TaxonomyTermObjectTypeResolverPickerRegistryInterface;
use PoPCMSSchema\Taxonomies\TypeAPIs\QueryableTaxonomyTermTypeAPIInterface;
use PoPCMSSchema\Taxonomies\TypeResolvers\ObjectType\GenericTaxonomyTermObjectTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\ObjectTypeResolverPickers\AbstractObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractGenericTaxonomyTermObjectTypeResolverPicker extends AbstractObjectTypeResolverPicker implements TaxonomyTermObjectTypeResolverPickerInterface
{
    /**
     * @var string[]|null
     */
    protected ?array $genericTaxonomyTermTaxonomies = null;
    /**
     * @var string[]|null
     */
    protected ?array $nonGenericTaxonomyTermTaxonomies = null;

    private ?GenericTaxonomyTermObjectTypeResolver $genericTaxonomyTermObjectTypeResolver = null;
    private ?QueryableTaxonomyTermTypeAPIInterface $queryableTaxonomyTermTypeAPI = null;
    private ?TaxonomyTermObjectTypeResolverPickerRegistryInterface $taxonomyTermObjectTypeResolverPickerRegistry = null;

    final protected function getGenericTaxonomyTermObjectTypeResolver(): GenericTaxonomyTermObjectTypeResolver
    {
        if ($this->genericTaxonomyTermObjectTypeResolver === null) {
            /** @var GenericTaxonomyTermObjectTypeResolver */
            $genericTaxonomyTermObjectTypeResolver = $this->instanceManager->getInstance(GenericTaxonomyTermObjectTypeResolver::class);
            $this->genericTaxonomyTermObjectTypeResolver = $genericTaxonomyTermObjectTypeResolver;
        }
        return $this->genericTaxonomyTermObjectTypeResolver;
    }
    final protected function getQueryableTaxonomyTermTypeAPI(): QueryableTaxonomyTermTypeAPIInterface
    {
        if ($this->queryableTaxonomyTermTypeAPI === null) {
            /** @var QueryableTaxonomyTermTypeAPIInterface */
            $queryableTaxonomyTermTypeAPI = $this->instanceManager->getInstance(QueryableTaxonomyTermTypeAPIInterface::class);
            $this->queryableTaxonomyTermTypeAPI = $queryableTaxonomyTermTypeAPI;
        }
        return $this->queryableTaxonomyTermTypeAPI;
    }
    final protected function getTaxonomyTermObjectTypeResolverPickerRegistry(): TaxonomyTermObjectTypeResolverPickerRegistryInterface
    {
        if ($this->taxonomyTermObjectTypeResolverPickerRegistry === null) {
            /** @var TaxonomyTermObjectTypeResolverPickerRegistryInterface */
            $taxonomyTermObjectTypeResolverPickerRegistry = $this->instanceManager->getInstance(TaxonomyTermObjectTypeResolverPickerRegistryInterface::class);
            $this->taxonomyTermObjectTypeResolverPickerRegistry = $taxonomyTermObjectTypeResolverPickerRegistry;
        }
        return $this->taxonomyTermObjectTypeResolverPickerRegistry;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getGenericTaxonomyTermObjectTypeResolver();
    }

    public function isInstanceOfType(object $object): bool
    {
        return $this->getQueryableTaxonomyTermTypeAPI()->isInstanceOfTaxonomyTermType($object);
    }

    public function isIDOfType(string|int $objectID): bool
    {
        return $this->getQueryableTaxonomyTermTypeAPI()->taxonomyTermExists($objectID);
    }

    /**
     * Process last, as to allow specific Pickers to take precedence,
     * such as for PostTaxonomyTerm. Only when no other Picker is available,
     * will GenericTaxonomyTerm be used.
     */
    public function getPriorityToAttachToClasses(): int
    {
        return 0;
    }

    /**
     * Check if there are generic taxonomyTerm taxonomies,
     * and only then enable it
     */
    public function isServiceEnabled(): bool
    {
        return $this->getGenericTaxonomyTermTaxonomies() !== [];
    }

    /**
     * @return string[]
     */
    protected function getGenericTaxonomyTermTaxonomies(): array
    {
        if ($this->genericTaxonomyTermTaxonomies === null) {
            $this->genericTaxonomyTermTaxonomies = $this->doGetGenericTaxonomyTermTaxonomies();
        }
        return $this->genericTaxonomyTermTaxonomies;
    }

    /**
     * @return string[]
     */
    protected function doGetGenericTaxonomyTermTaxonomies(): array
    {
        // @todo Add config for this and fix
        return [];
        // /** @var ModuleConfiguration */
        // $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        // return array_diff(
        //     $moduleConfiguration->getQueryableTaxonomyTermTaxonomies(),
        //     $this->getNonGenericTaxonomyTermTaxonomies()
        // );
    }

    /**
     * @return string[]
     */
    protected function getNonGenericTaxonomyTermTaxonomies(): array
    {
        if ($this->nonGenericTaxonomyTermTaxonomies === null) {
            $this->nonGenericTaxonomyTermTaxonomies = $this->doGetNonGenericTaxonomyTermTaxonomies();
        }
        return $this->nonGenericTaxonomyTermTaxonomies;
    }

    /**
     * @return string[]
     */
    protected function doGetNonGenericTaxonomyTermTaxonomies(): array
    {
        $taxonomyTermObjectTypeResolverPickers = $this->getTaxonomyTermObjectTypeResolverPickerRegistry()->getTaxonomyTermObjectTypeResolverPickers();
        $nonGenericTaxonomyTermTaxonomies = [];
        foreach ($taxonomyTermObjectTypeResolverPickers as $taxonomyTermObjectTypeResolverPicker) {
            // Skip this class, we're interested in all the non-generic ones
            if ($taxonomyTermObjectTypeResolverPicker === $this) {
                continue;
            }
            $nonGenericTaxonomyTermTaxonomies = [
                ...$nonGenericTaxonomyTermTaxonomies,
                ...$taxonomyTermObjectTypeResolverPicker->getTaxonomyTermTaxonomies(),
            ];
        }

        return $nonGenericTaxonomyTermTaxonomies;
    }

    /**
     * Return empty value is OK, because this method will
     * never be called on this class.
     *
     * @see `isServiceEnabled`
     *
     * @return string[]
     */
    public function getTaxonomyTermTaxonomies(): array
    {
        return [];
    }
}
