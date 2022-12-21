<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\ObjectTypeResolverPickers;

use PoPCMSSchema\Tags\Module;
use PoPCMSSchema\Tags\ModuleConfiguration;
use PoPCMSSchema\Tags\Registries\TagObjectTypeResolverPickerRegistryInterface;
use PoPCMSSchema\Tags\TypeAPIs\QueryableTagTypeAPIInterface;
use PoPCMSSchema\Tags\TypeResolvers\ObjectType\GenericTagObjectTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\ObjectTypeResolverPickers\AbstractObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractGenericTagObjectTypeResolverPicker extends AbstractObjectTypeResolverPicker implements TagObjectTypeResolverPickerInterface
{
    private ?GenericTagObjectTypeResolver $genericTagObjectTypeResolver = null;
    private ?QueryableTagTypeAPIInterface $queryableTagTypeAPI = null;
    private ?TagObjectTypeResolverPickerRegistryInterface $tagObjectTypeResolverPickerRegistry = null;

    final public function setGenericTagObjectTypeResolver(GenericTagObjectTypeResolver $genericTagObjectTypeResolver): void
    {
        $this->genericTagObjectTypeResolver = $genericTagObjectTypeResolver;
    }
    final protected function getGenericTagObjectTypeResolver(): GenericTagObjectTypeResolver
    {
        /** @var GenericTagObjectTypeResolver */
        return $this->genericTagObjectTypeResolver ??= $this->instanceManager->getInstance(GenericTagObjectTypeResolver::class);
    }
    final public function setQueryableTagTypeAPI(QueryableTagTypeAPIInterface $queryableTagTypeAPI): void
    {
        $this->queryableTagTypeAPI = $queryableTagTypeAPI;
    }
    final protected function getQueryableTagTypeAPI(): QueryableTagTypeAPIInterface
    {
        /** @var QueryableTagTypeAPIInterface */
        return $this->queryableTagTypeAPI ??= $this->instanceManager->getInstance(QueryableTagTypeAPIInterface::class);
    }
    final public function setTagObjectTypeResolverPickerRegistry(TagObjectTypeResolverPickerRegistryInterface $tagObjectTypeResolverPickerRegistry): void
    {
        $this->tagObjectTypeResolverPickerRegistry = $tagObjectTypeResolverPickerRegistry;
    }
    final protected function getTagObjectTypeResolverPickerRegistry(): TagObjectTypeResolverPickerRegistryInterface
    {
        /** @var TagObjectTypeResolverPickerRegistryInterface */
        return $this->tagObjectTypeResolverPickerRegistry ??= $this->instanceManager->getInstance(TagObjectTypeResolverPickerRegistryInterface::class);
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getGenericTagObjectTypeResolver();
    }

    public function isInstanceOfType(object $object): bool
    {
        return $this->getQueryableTagTypeAPI()->isInstanceOfTagType($object);
    }

    public function isIDOfType(string|int $objectID): bool
    {
        return $this->getQueryableTagTypeAPI()->tagExists($objectID);
    }

    /**
     * Process last, as to allow specific Pickers to take precedence,
     * such as for PostTag. Only when no other Picker is available,
     * will GenericTag be used.
     */
    public function getPriorityToAttachToClasses(): int
    {
        return 0;
    }

    /**
     * Check if there are generic tag taxonomies,
     * and only then enable it
     */
    public function isServiceEnabled(): bool
    {
        $tagObjectTypeResolverPickers = $this->getTagObjectTypeResolverPickerRegistry()->getTagObjectTypeResolverPickers();
        $nonGenericTagTaxonomies = [];
        foreach ($tagObjectTypeResolverPickers as $tagObjectTypeResolverPicker) {
            // Skip this class, we're interested in all the non-generic ones
            if ($tagObjectTypeResolverPicker === $this) {
                continue;
            }
            $nonGenericTagTaxonomies[] = $tagObjectTypeResolverPicker->getTagTaxonomy();
        }

        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return array_diff(
            $moduleConfiguration->getQueryableTagTaxonomies(),
            $nonGenericTagTaxonomies
        ) !== [];
    }
    
    /**
     * Return empty value is OK, because this method will
     * never be called on this class.
     *
     * @see `isServiceEnabled`
     */
    public function getTagTaxonomy(): string
    {
        return '';
    }
}
