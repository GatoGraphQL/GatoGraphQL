<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPosts\Module;
use PoPCMSSchema\CustomPosts\ModuleConfiguration;
use PoPCMSSchema\CustomPosts\Registries\CustomPostObjectTypeResolverPickerRegistryInterface;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\GenericCustomPostObjectTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\ObjectTypeResolverPickers\AbstractObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractGenericCustomPostObjectTypeResolverPicker extends AbstractObjectTypeResolverPicker implements CustomPostObjectTypeResolverPickerInterface
{
    private ?GenericCustomPostObjectTypeResolver $genericCustomPostObjectTypeResolver = null;
    private ?CustomPostTypeAPIInterface $customPostTypeAPI = null;
    private ?CustomPostObjectTypeResolverPickerRegistryInterface $customPostObjectTypeResolverPickerRegistry = null;

    final public function setGenericCustomPostObjectTypeResolver(GenericCustomPostObjectTypeResolver $genericCustomPostObjectTypeResolver): void
    {
        $this->genericCustomPostObjectTypeResolver = $genericCustomPostObjectTypeResolver;
    }
    final protected function getGenericCustomPostObjectTypeResolver(): GenericCustomPostObjectTypeResolver
    {
        /** @var GenericCustomPostObjectTypeResolver */
        return $this->genericCustomPostObjectTypeResolver ??= $this->instanceManager->getInstance(GenericCustomPostObjectTypeResolver::class);
    }
    final public function setCustomPostTypeAPI(CustomPostTypeAPIInterface $customPostTypeAPI): void
    {
        $this->customPostTypeAPI = $customPostTypeAPI;
    }
    final protected function getCustomPostTypeAPI(): CustomPostTypeAPIInterface
    {
        /** @var CustomPostTypeAPIInterface */
        return $this->customPostTypeAPI ??= $this->instanceManager->getInstance(CustomPostTypeAPIInterface::class);
    }
    final public function setCustomPostObjectTypeResolverPickerRegistry(CustomPostObjectTypeResolverPickerRegistryInterface $customPostObjectTypeResolverPickerRegistry): void
    {
        $this->customPostObjectTypeResolverPickerRegistry = $customPostObjectTypeResolverPickerRegistry;
    }
    final protected function getCustomPostObjectTypeResolverPickerRegistry(): CustomPostObjectTypeResolverPickerRegistryInterface
    {
        /** @var CustomPostObjectTypeResolverPickerRegistryInterface */
        return $this->customPostObjectTypeResolverPickerRegistry ??= $this->instanceManager->getInstance(CustomPostObjectTypeResolverPickerRegistryInterface::class);
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getGenericCustomPostObjectTypeResolver();
    }

    public function isInstanceOfType(object $object): bool
    {
        return $this->getCustomPostTypeAPI()->isInstanceOfCustomPostType($object);
    }

    public function isIDOfType(string|int $objectID): bool
    {
        return $this->getCustomPostTypeAPI()->customPostExists($objectID);
    }

    /**
     * Process last, as to allow specific Pickers to take precedence,
     * such as for Post or Page. Only when no other Picker is available,
     * will GenericCustomPost be used.
     */
    public function getPriorityToAttachToClasses(): int
    {
        return 0;
    }

    /**
     * Check if there are generic custom post types,
     * and only then enable it
     */
    public function isServiceEnabled(): bool
    {
        $customPostObjectTypeResolverPickers = $this->getCustomPostObjectTypeResolverPickerRegistry()->getCustomPostObjectTypeResolverPickers();
        $nonGenericCustomPostTypes = [];
        foreach ($customPostObjectTypeResolverPickers as $customPostObjectTypeResolverPicker) {
            // Skip this class, we're interested in all the non-generic ones
            if ($customPostObjectTypeResolverPicker === $this) {
                continue;
            }
            $nonGenericCustomPostTypes[] = $customPostObjectTypeResolverPicker->getCustomPostType();
        }
        
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return array_diff(
            $moduleConfiguration->getQueryableCustomPostTypes(),
            $nonGenericCustomPostTypes
        ) !== [];
    }

    /**
     * Return empty value is OK, because this method will
     * never be called on this class.
     *
     * @see `isServiceEnabled`
     */
    public function getCustomPostType(): string
    {
        return '';
    }
}
