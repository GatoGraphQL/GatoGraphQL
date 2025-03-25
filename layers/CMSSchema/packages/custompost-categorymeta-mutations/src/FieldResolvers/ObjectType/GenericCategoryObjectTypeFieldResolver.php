<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CategoryMutations\FieldResolvers\ObjectType\AbstractCategoryObjectTypeFieldResolver;
use PoPCMSSchema\CategoryMutations\Module as CategoryMutationsModule;
use PoPCMSSchema\CategoryMutations\ModuleConfiguration as CategoryMutationsModuleConfiguration;
use PoPCMSSchema\Categories\TypeResolvers\ObjectType\GenericCategoryObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\DeleteGenericCategoryTermMetaMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\CreateGenericCategoryTermMetaMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\PayloadableDeleteGenericCategoryTermMetaMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\PayloadableCreateGenericCategoryTermMetaMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\PayloadableUpdateGenericCategoryTermMetaMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\UpdateGenericCategoryTermMetaMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType\GenericCategoryTermMetaCreateInputObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType\GenericCategoryTermMetaDeleteInputObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType\GenericCategoryTermMetaUpdateInputObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\ObjectType\GenericCategoryCreateMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\ObjectType\GenericCategoryDeleteMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\ObjectType\GenericCategoryUpdateMetaMutationPayloadObjectTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;

class GenericCategoryObjectTypeFieldResolver extends AbstractCategoryObjectTypeFieldResolver
{
    private ?GenericCategoryObjectTypeResolver $genericCategoryObjectTypeResolver = null;
    private ?GenericCategoryDeleteMetaMutationPayloadObjectTypeResolver $genericCategoryDeleteMetaMutationPayloadObjectTypeResolver = null;
    private ?GenericCategoryCreateMetaMutationPayloadObjectTypeResolver $genericCategoryCreateMutationPayloadObjectTypeResolver = null;
    private ?GenericCategoryUpdateMetaMutationPayloadObjectTypeResolver $genericCategoryUpdateMetaMutationPayloadObjectTypeResolver = null;
    private ?UpdateGenericCategoryTermMetaMutationResolver $updateGenericCategoryTermMetaMutationResolver = null;
    private ?DeleteGenericCategoryTermMetaMutationResolver $deleteGenericCategoryTermMetaMutationResolver = null;
    private ?CreateGenericCategoryTermMetaMutationResolver $createGenericCategoryTermMetaMutationResolver = null;
    private ?PayloadableDeleteGenericCategoryTermMetaMutationResolver $payloadableDeleteGenericCategoryTermMetaMutationResolver = null;
    private ?PayloadableCreateGenericCategoryTermMetaMutationResolver $payloadableCreateGenericCategoryTermMetaMutationResolver = null;
    private ?PayloadableUpdateGenericCategoryTermMetaMutationResolver $payloadableUpdateGenericCategoryTermMetaMutationResolver = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?GenericCategoryTermMetaCreateInputObjectTypeResolver $genericCategoryTermMetaCreateInputObjectTypeResolver = null;
    private ?GenericCategoryTermMetaDeleteInputObjectTypeResolver $genericCategoryTermMetaDeleteInputObjectTypeResolver = null;
    private ?GenericCategoryTermMetaUpdateInputObjectTypeResolver $genericCategoryTermMetaUpdateInputObjectTypeResolver = null;

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
    final protected function getGenericCategoryCreateMetaMutationPayloadObjectTypeResolver(): GenericCategoryCreateMetaMutationPayloadObjectTypeResolver
    {
        if ($this->genericCategoryCreateMutationPayloadObjectTypeResolver === null) {
            /** @var GenericCategoryCreateMetaMutationPayloadObjectTypeResolver */
            $genericCategoryCreateMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(GenericCategoryCreateMetaMutationPayloadObjectTypeResolver::class);
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
    final protected function getUpdateGenericCategoryTermMetaMutationResolver(): UpdateGenericCategoryTermMetaMutationResolver
    {
        if ($this->updateGenericCategoryTermMetaMutationResolver === null) {
            /** @var UpdateGenericCategoryTermMetaMutationResolver */
            $updateGenericCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(UpdateGenericCategoryTermMetaMutationResolver::class);
            $this->updateGenericCategoryTermMetaMutationResolver = $updateGenericCategoryTermMetaMutationResolver;
        }
        return $this->updateGenericCategoryTermMetaMutationResolver;
    }
    final protected function getCreateGenericCategoryTermMetaMutationResolver(): CreateGenericCategoryTermMetaMutationResolver
    {
        if ($this->createGenericCategoryTermMetaMutationResolver === null) {
            /** @var CreateGenericCategoryTermMetaMutationResolver */
            $createGenericCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(CreateGenericCategoryTermMetaMutationResolver::class);
            $this->createGenericCategoryTermMetaMutationResolver = $createGenericCategoryTermMetaMutationResolver;
        }
        return $this->createGenericCategoryTermMetaMutationResolver;
    }
    final protected function getDeleteGenericCategoryTermMetaMutationResolver(): DeleteGenericCategoryTermMetaMutationResolver
    {
        if ($this->deleteGenericCategoryTermMetaMutationResolver === null) {
            /** @var DeleteGenericCategoryTermMetaMutationResolver */
            $deleteGenericCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(DeleteGenericCategoryTermMetaMutationResolver::class);
            $this->deleteGenericCategoryTermMetaMutationResolver = $deleteGenericCategoryTermMetaMutationResolver;
        }
        return $this->deleteGenericCategoryTermMetaMutationResolver;
    }
    final protected function getPayloadableDeleteGenericCategoryTermMetaMutationResolver(): PayloadableDeleteGenericCategoryTermMetaMutationResolver
    {
        if ($this->payloadableDeleteGenericCategoryTermMetaMutationResolver === null) {
            /** @var PayloadableDeleteGenericCategoryTermMetaMutationResolver */
            $payloadableDeleteGenericCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteGenericCategoryTermMetaMutationResolver::class);
            $this->payloadableDeleteGenericCategoryTermMetaMutationResolver = $payloadableDeleteGenericCategoryTermMetaMutationResolver;
        }
        return $this->payloadableDeleteGenericCategoryTermMetaMutationResolver;
    }
    final protected function getPayloadableCreateGenericCategoryTermMetaMutationResolver(): PayloadableCreateGenericCategoryTermMetaMutationResolver
    {
        if ($this->payloadableCreateGenericCategoryTermMetaMutationResolver === null) {
            /** @var PayloadableCreateGenericCategoryTermMetaMutationResolver */
            $payloadableCreateGenericCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(PayloadableCreateGenericCategoryTermMetaMutationResolver::class);
            $this->payloadableCreateGenericCategoryTermMetaMutationResolver = $payloadableCreateGenericCategoryTermMetaMutationResolver;
        }
        return $this->payloadableCreateGenericCategoryTermMetaMutationResolver;
    }
    final protected function getPayloadableUpdateGenericCategoryTermMetaMutationResolver(): PayloadableUpdateGenericCategoryTermMetaMutationResolver
    {
        if ($this->payloadableUpdateGenericCategoryTermMetaMutationResolver === null) {
            /** @var PayloadableUpdateGenericCategoryTermMetaMutationResolver */
            $payloadableUpdateGenericCategoryTermMetaMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateGenericCategoryTermMetaMutationResolver::class);
            $this->payloadableUpdateGenericCategoryTermMetaMutationResolver = $payloadableUpdateGenericCategoryTermMetaMutationResolver;
        }
        return $this->payloadableUpdateGenericCategoryTermMetaMutationResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        if ($this->booleanScalarTypeResolver === null) {
            /** @var BooleanScalarTypeResolver */
            $booleanScalarTypeResolver = $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
            $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
        }
        return $this->booleanScalarTypeResolver;
    }
    final protected function getGenericCategoryTermMetaCreateInputObjectTypeResolver(): GenericCategoryTermMetaCreateInputObjectTypeResolver
    {
        if ($this->genericCategoryTermMetaCreateInputObjectTypeResolver === null) {
            /** @var GenericCategoryTermMetaCreateInputObjectTypeResolver */
            $genericCategoryTermMetaCreateInputObjectTypeResolver = $this->instanceManager->getInstance(GenericCategoryTermMetaCreateInputObjectTypeResolver::class);
            $this->genericCategoryTermMetaCreateInputObjectTypeResolver = $genericCategoryTermMetaCreateInputObjectTypeResolver;
        }
        return $this->genericCategoryTermMetaCreateInputObjectTypeResolver;
    }
    final protected function getGenericCategoryTermMetaDeleteInputObjectTypeResolver(): GenericCategoryTermMetaDeleteInputObjectTypeResolver
    {
        if ($this->genericCategoryTermMetaDeleteInputObjectTypeResolver === null) {
            /** @var GenericCategoryTermMetaDeleteInputObjectTypeResolver */
            $genericCategoryTermMetaDeleteInputObjectTypeResolver = $this->instanceManager->getInstance(GenericCategoryTermMetaDeleteInputObjectTypeResolver::class);
            $this->genericCategoryTermMetaDeleteInputObjectTypeResolver = $genericCategoryTermMetaDeleteInputObjectTypeResolver;
        }
        return $this->genericCategoryTermMetaDeleteInputObjectTypeResolver;
    }
    final protected function getGenericCategoryTermMetaUpdateInputObjectTypeResolver(): GenericCategoryTermMetaUpdateInputObjectTypeResolver
    {
        if ($this->genericCategoryTermMetaUpdateInputObjectTypeResolver === null) {
            /** @var GenericCategoryTermMetaUpdateInputObjectTypeResolver */
            $genericCategoryTermMetaUpdateInputObjectTypeResolver = $this->instanceManager->getInstance(GenericCategoryTermMetaUpdateInputObjectTypeResolver::class);
            $this->genericCategoryTermMetaUpdateInputObjectTypeResolver = $genericCategoryTermMetaUpdateInputObjectTypeResolver;
        }
        return $this->genericCategoryTermMetaUpdateInputObjectTypeResolver;
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

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'addMeta' => [
                'input' => $this->getGenericCategoryTermMetaCreateInputObjectTypeResolver(),
            ],
            'updateMeta' => [
                'input' => $this->getGenericCategoryTermMetaUpdateInputObjectTypeResolver(),
            ],
            'deleteMeta' => [
                'input' => $this->getGenericCategoryTermMetaDeleteInputObjectTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        /** @var CategoryMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(CategoryMutationsModule::class)->getConfiguration();
        $usePayloadableCategoryMutations = $moduleConfiguration->usePayloadableCategoryMutations();
        return match ($fieldName) {
            'addMeta' => $usePayloadableCategoryMutations
                ? $this->getPayloadableCreateGenericCategoryTermMetaMutationResolver()
                : $this->getCreateGenericCategoryTermMetaMutationResolver(),
            'updateMeta' => $usePayloadableCategoryMutations
                ? $this->getPayloadableUpdateGenericCategoryTermMetaMutationResolver()
                : $this->getUpdateGenericCategoryTermMetaMutationResolver(),
            'deleteMeta' => $usePayloadableCategoryMutations
                ? $this->getPayloadableDeleteGenericCategoryTermMetaMutationResolver()
                : $this->getDeleteGenericCategoryTermMetaMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var CategoryMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(CategoryMutationsModule::class)->getConfiguration();
        $usePayloadableCategoryMutations = $moduleConfiguration->usePayloadableCategoryMutations();
        if (!$usePayloadableCategoryMutations) {
            return match ($fieldName) {
                'addMeta',
                'updateMeta',
                'deleteMeta'
                    => $this->getGenericCategoryObjectTypeResolver(),
                default
                    => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'addMeta' => $this->getGenericCategoryCreateMetaMutationPayloadObjectTypeResolver(),
            'updateMeta' => $this->getGenericCategoryUpdateMetaMutationPayloadObjectTypeResolver(),
            'deleteMeta' => $this->getGenericCategoryDeleteMetaMutationPayloadObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
