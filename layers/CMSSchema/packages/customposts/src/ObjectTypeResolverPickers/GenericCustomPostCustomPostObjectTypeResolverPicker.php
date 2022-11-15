<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPosts\Module;
use PoPCMSSchema\CustomPosts\ModuleConfiguration;
use PoPCMSSchema\CustomPosts\Registries\CustomPostObjectTypeResolverPickerRegistryInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\GenericCustomPostObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class GenericCustomPostCustomPostObjectTypeResolverPicker extends AbstractCustomPostObjectTypeResolverPicker
{
    /**
     * Implement trait only for consistency with all other
     * CustomPostObjectTypeResolverPickers, but otherwise
     * none of its methods is actually needed.
     */
    use CustomPostObjectTypeResolverPickerTrait;

    private ?GenericCustomPostObjectTypeResolver $genericCustomPostObjectTypeResolver = null;
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
    
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            CustomPostUnionTypeResolver::class,
        ];
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
        $customPostTypes = $moduleConfiguration->getQueryableCustomPostTypes();
        return array_diff($customPostTypes, $nonGenericCustomPostTypes) !== [];
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
