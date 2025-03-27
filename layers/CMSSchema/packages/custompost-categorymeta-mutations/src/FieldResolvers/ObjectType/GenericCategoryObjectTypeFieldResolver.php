<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CategoryMetaMutations\FieldResolvers\ObjectType\AbstractCategoryObjectTypeFieldResolver;
use PoPCMSSchema\CategoryMetaMutations\Module as CategoryMetaMutationsModule;
use PoPCMSSchema\CategoryMetaMutations\ModuleConfiguration as CategoryMetaMutationsModuleConfiguration;
use PoPCMSSchema\Categories\TypeResolvers\ObjectType\GenericCategoryObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\ObjectType\GenericCategoryAddMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\ObjectType\GenericCategoryDeleteMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\ObjectType\GenericCategorySetMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\ObjectType\GenericCategoryUpdateMetaMutationPayloadObjectTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericCategoryObjectTypeFieldResolver extends AbstractCategoryObjectTypeFieldResolver
{
    private ?GenericCategoryObjectTypeResolver $genericCategoryObjectTypeResolver = null;
    private ?GenericCategoryDeleteMetaMutationPayloadObjectTypeResolver $genericCategoryDeleteMetaMutationPayloadObjectTypeResolver = null;
    private ?GenericCategoryAddMetaMutationPayloadObjectTypeResolver $genericCategoryCreateMutationPayloadObjectTypeResolver = null;
    private ?GenericCategoryUpdateMetaMutationPayloadObjectTypeResolver $genericCategoryUpdateMetaMutationPayloadObjectTypeResolver = null;
    private ?GenericCategorySetMetaMutationPayloadObjectTypeResolver $genericCategorySetMetaMutationPayloadObjectTypeResolver = null;

    final protected function getGenericCategoryObjectTypeResolver(): GenericCategoryObjectTypeResolver
    {
        if ($this->genericCategoryObjectTypeResolver === null) {
            /** @var GenericCategoryObjectTypeResolver */
            $genericCategoryObjectTypeResolver = $this->instanceManager->getInstance(GenericCategoryObjectTypeResolver::class);
            $this->genericCategoryObjectTypeResolver = $genericCategoryObjectTypeResolver;
        }
        return $this->genericCategoryObjectTypeResolver;
    }
    final protected function getGenericCategoryDeleteMetaMutationPayloadObjectTypeResolver(): GenericCategoryDeleteMetaMutationPayloadObjectTypeResolver
    {
        if ($this->genericCategoryDeleteMetaMutationPayloadObjectTypeResolver === null) {
            /** @var GenericCategoryDeleteMetaMutationPayloadObjectTypeResolver */
            $genericCategoryDeleteMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(GenericCategoryDeleteMetaMutationPayloadObjectTypeResolver::class);
            $this->genericCategoryDeleteMetaMutationPayloadObjectTypeResolver = $genericCategoryDeleteMetaMutationPayloadObjectTypeResolver;
        }
        return $this->genericCategoryDeleteMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getGenericCategoryAddMetaMutationPayloadObjectTypeResolver(): GenericCategoryAddMetaMutationPayloadObjectTypeResolver
    {
        if ($this->genericCategoryCreateMutationPayloadObjectTypeResolver === null) {
            /** @var GenericCategoryAddMetaMutationPayloadObjectTypeResolver */
            $genericCategoryCreateMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(GenericCategoryAddMetaMutationPayloadObjectTypeResolver::class);
            $this->genericCategoryCreateMutationPayloadObjectTypeResolver = $genericCategoryCreateMutationPayloadObjectTypeResolver;
        }
        return $this->genericCategoryCreateMutationPayloadObjectTypeResolver;
    }
    final protected function getGenericCategoryUpdateMetaMutationPayloadObjectTypeResolver(): GenericCategoryUpdateMetaMutationPayloadObjectTypeResolver
    {
        if ($this->genericCategoryUpdateMetaMutationPayloadObjectTypeResolver === null) {
            /** @var GenericCategoryUpdateMetaMutationPayloadObjectTypeResolver */
            $genericCategoryUpdateMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(GenericCategoryUpdateMetaMutationPayloadObjectTypeResolver::class);
            $this->genericCategoryUpdateMetaMutationPayloadObjectTypeResolver = $genericCategoryUpdateMetaMutationPayloadObjectTypeResolver;
        }
        return $this->genericCategoryUpdateMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getGenericCategorySetMetaMutationPayloadObjectTypeResolver(): GenericCategorySetMetaMutationPayloadObjectTypeResolver
    {
        if ($this->genericCategorySetMetaMutationPayloadObjectTypeResolver === null) {
            /** @var GenericCategorySetMetaMutationPayloadObjectTypeResolver */
            $genericCategorySetMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(GenericCategorySetMetaMutationPayloadObjectTypeResolver::class);
            $this->genericCategorySetMetaMutationPayloadObjectTypeResolver = $genericCategorySetMetaMutationPayloadObjectTypeResolver;
        }
        return $this->genericCategorySetMetaMutationPayloadObjectTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            GenericCategoryObjectTypeResolver::class,
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var CategoryMetaMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(CategoryMetaMutationsModule::class)->getConfiguration();
        $usePayloadableCategoryMetaMutations = $moduleConfiguration->usePayloadableCategoryMetaMutations();
        if (!$usePayloadableCategoryMetaMutations) {
            return match ($fieldName) {
                'addMeta',
                'deleteMeta',
                'setMeta',
                'updateMeta'
                    => $this->getGenericCategoryObjectTypeResolver(),
                default
                    => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'addMeta' => $this->getGenericCategoryAddMetaMutationPayloadObjectTypeResolver(),
            'deleteMeta' => $this->getGenericCategoryDeleteMetaMutationPayloadObjectTypeResolver(),
            'setMeta' => $this->getGenericCategorySetMetaMutationPayloadObjectTypeResolver(),
            'updateMeta' => $this->getGenericCategoryUpdateMetaMutationPayloadObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
