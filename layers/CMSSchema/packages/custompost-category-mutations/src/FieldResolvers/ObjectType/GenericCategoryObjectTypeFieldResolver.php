<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CategoryMutations\FieldResolvers\ObjectType\AbstractCategoryObjectTypeFieldResolver;
use PoPCMSSchema\CategoryMutations\Module as CategoryMutationsModule;
use PoPCMSSchema\CategoryMutations\ModuleConfiguration as CategoryMutationsModuleConfiguration;
use PoPCMSSchema\Categories\TypeResolvers\ObjectType\GenericCategoryObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers\DeleteGenericCategoryTermMutationResolver;
use PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers\PayloadableDeleteGenericCategoryTermMutationResolver;
use PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers\PayloadableUpdateGenericCategoryTermMutationResolver;
use PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers\UpdateGenericCategoryTermMutationResolver;
use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\InputObjectType\GenericCategoryTermUpdateInputObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\ObjectType\GenericCategoryDeleteMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\ObjectType\GenericCategoryUpdateMutationPayloadObjectTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;

class GenericCategoryObjectTypeFieldResolver extends AbstractCategoryObjectTypeFieldResolver
{
    private ?GenericCategoryObjectTypeResolver $genericCategoryObjectTypeResolver = null;
    private ?GenericCategoryUpdateMutationPayloadObjectTypeResolver $genericCategoryUpdateMutationPayloadObjectTypeResolver = null;
    private ?GenericCategoryDeleteMutationPayloadObjectTypeResolver $genericCategoryDeleteMutationPayloadObjectTypeResolver = null;
    private ?UpdateGenericCategoryTermMutationResolver $updateGenericCategoryTermMutationResolver = null;
    private ?DeleteGenericCategoryTermMutationResolver $deleteGenericCategoryTermMutationResolver = null;
    private ?PayloadableUpdateGenericCategoryTermMutationResolver $payloadableUpdateGenericCategoryTermMutationResolver = null;
    private ?PayloadableDeleteGenericCategoryTermMutationResolver $payloadableDeleteGenericCategoryTermMutationResolver = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?GenericCategoryTermUpdateInputObjectTypeResolver $genericCategoryTermUpdateInputObjectTypeResolver = null;

    final public function setGenericCategoryObjectTypeResolver(GenericCategoryObjectTypeResolver $genericCategoryObjectTypeResolver): void
    {
        $this->genericCategoryObjectTypeResolver = $genericCategoryObjectTypeResolver;
    }
    final protected function getGenericCategoryObjectTypeResolver(): GenericCategoryObjectTypeResolver
    {
        if ($this->genericCategoryObjectTypeResolver === null) {
            /** @var GenericCategoryObjectTypeResolver */
            $genericCategoryObjectTypeResolver = $this->instanceManager->getInstance(GenericCategoryObjectTypeResolver::class);
            $this->genericCategoryObjectTypeResolver = $genericCategoryObjectTypeResolver;
        }
        return $this->genericCategoryObjectTypeResolver;
    }
    final public function setGenericCategoryUpdateMutationPayloadObjectTypeResolver(GenericCategoryUpdateMutationPayloadObjectTypeResolver $genericCategoryUpdateMutationPayloadObjectTypeResolver): void
    {
        $this->genericCategoryUpdateMutationPayloadObjectTypeResolver = $genericCategoryUpdateMutationPayloadObjectTypeResolver;
    }
    final protected function getGenericCategoryUpdateMutationPayloadObjectTypeResolver(): GenericCategoryUpdateMutationPayloadObjectTypeResolver
    {
        if ($this->genericCategoryUpdateMutationPayloadObjectTypeResolver === null) {
            /** @var GenericCategoryUpdateMutationPayloadObjectTypeResolver */
            $genericCategoryUpdateMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(GenericCategoryUpdateMutationPayloadObjectTypeResolver::class);
            $this->genericCategoryUpdateMutationPayloadObjectTypeResolver = $genericCategoryUpdateMutationPayloadObjectTypeResolver;
        }
        return $this->genericCategoryUpdateMutationPayloadObjectTypeResolver;
    }
    final public function setGenericCategoryDeleteMutationPayloadObjectTypeResolver(GenericCategoryDeleteMutationPayloadObjectTypeResolver $genericCategoryDeleteMutationPayloadObjectTypeResolver): void
    {
        $this->genericCategoryDeleteMutationPayloadObjectTypeResolver = $genericCategoryDeleteMutationPayloadObjectTypeResolver;
    }
    final protected function getGenericCategoryDeleteMutationPayloadObjectTypeResolver(): GenericCategoryDeleteMutationPayloadObjectTypeResolver
    {
        if ($this->genericCategoryDeleteMutationPayloadObjectTypeResolver === null) {
            /** @var GenericCategoryDeleteMutationPayloadObjectTypeResolver */
            $genericCategoryDeleteMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(GenericCategoryDeleteMutationPayloadObjectTypeResolver::class);
            $this->genericCategoryDeleteMutationPayloadObjectTypeResolver = $genericCategoryDeleteMutationPayloadObjectTypeResolver;
        }
        return $this->genericCategoryDeleteMutationPayloadObjectTypeResolver;
    }
    final public function setUpdateGenericCategoryTermMutationResolver(UpdateGenericCategoryTermMutationResolver $updateGenericCategoryTermMutationResolver): void
    {
        $this->updateGenericCategoryTermMutationResolver = $updateGenericCategoryTermMutationResolver;
    }
    final protected function getUpdateGenericCategoryTermMutationResolver(): UpdateGenericCategoryTermMutationResolver
    {
        if ($this->updateGenericCategoryTermMutationResolver === null) {
            /** @var UpdateGenericCategoryTermMutationResolver */
            $updateGenericCategoryTermMutationResolver = $this->instanceManager->getInstance(UpdateGenericCategoryTermMutationResolver::class);
            $this->updateGenericCategoryTermMutationResolver = $updateGenericCategoryTermMutationResolver;
        }
        return $this->updateGenericCategoryTermMutationResolver;
    }
    final public function setDeleteGenericCategoryTermMutationResolver(DeleteGenericCategoryTermMutationResolver $deleteGenericCategoryTermMutationResolver): void
    {
        $this->deleteGenericCategoryTermMutationResolver = $deleteGenericCategoryTermMutationResolver;
    }
    final protected function getDeleteGenericCategoryTermMutationResolver(): DeleteGenericCategoryTermMutationResolver
    {
        if ($this->deleteGenericCategoryTermMutationResolver === null) {
            /** @var DeleteGenericCategoryTermMutationResolver */
            $deleteGenericCategoryTermMutationResolver = $this->instanceManager->getInstance(DeleteGenericCategoryTermMutationResolver::class);
            $this->deleteGenericCategoryTermMutationResolver = $deleteGenericCategoryTermMutationResolver;
        }
        return $this->deleteGenericCategoryTermMutationResolver;
    }
    final public function setPayloadableUpdateGenericCategoryTermMutationResolver(PayloadableUpdateGenericCategoryTermMutationResolver $payloadableUpdateGenericCategoryTermMutationResolver): void
    {
        $this->payloadableUpdateGenericCategoryTermMutationResolver = $payloadableUpdateGenericCategoryTermMutationResolver;
    }
    final protected function getPayloadableUpdateGenericCategoryTermMutationResolver(): PayloadableUpdateGenericCategoryTermMutationResolver
    {
        if ($this->payloadableUpdateGenericCategoryTermMutationResolver === null) {
            /** @var PayloadableUpdateGenericCategoryTermMutationResolver */
            $payloadableUpdateGenericCategoryTermMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateGenericCategoryTermMutationResolver::class);
            $this->payloadableUpdateGenericCategoryTermMutationResolver = $payloadableUpdateGenericCategoryTermMutationResolver;
        }
        return $this->payloadableUpdateGenericCategoryTermMutationResolver;
    }
    final public function setPayloadableDeleteGenericCategoryTermMutationResolver(PayloadableDeleteGenericCategoryTermMutationResolver $payloadableDeleteGenericCategoryTermMutationResolver): void
    {
        $this->payloadableDeleteGenericCategoryTermMutationResolver = $payloadableDeleteGenericCategoryTermMutationResolver;
    }
    final protected function getPayloadableDeleteGenericCategoryTermMutationResolver(): PayloadableDeleteGenericCategoryTermMutationResolver
    {
        if ($this->payloadableDeleteGenericCategoryTermMutationResolver === null) {
            /** @var PayloadableDeleteGenericCategoryTermMutationResolver */
            $payloadableDeleteGenericCategoryTermMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteGenericCategoryTermMutationResolver::class);
            $this->payloadableDeleteGenericCategoryTermMutationResolver = $payloadableDeleteGenericCategoryTermMutationResolver;
        }
        return $this->payloadableDeleteGenericCategoryTermMutationResolver;
    }
    final public function setBooleanScalarTypeResolver(BooleanScalarTypeResolver $booleanScalarTypeResolver): void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
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
    final public function setGenericCategoryTermUpdateInputObjectTypeResolver(GenericCategoryTermUpdateInputObjectTypeResolver $genericCategoryTermUpdateInputObjectTypeResolver): void
    {
        $this->genericCategoryTermUpdateInputObjectTypeResolver = $genericCategoryTermUpdateInputObjectTypeResolver;
    }
    final protected function getGenericCategoryTermUpdateInputObjectTypeResolver(): GenericCategoryTermUpdateInputObjectTypeResolver
    {
        if ($this->genericCategoryTermUpdateInputObjectTypeResolver === null) {
            /** @var GenericCategoryTermUpdateInputObjectTypeResolver */
            $genericCategoryTermUpdateInputObjectTypeResolver = $this->instanceManager->getInstance(GenericCategoryTermUpdateInputObjectTypeResolver::class);
            $this->genericCategoryTermUpdateInputObjectTypeResolver = $genericCategoryTermUpdateInputObjectTypeResolver;
        }
        return $this->genericCategoryTermUpdateInputObjectTypeResolver;
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
            'update' => [
                'input' => $this->getGenericCategoryTermUpdateInputObjectTypeResolver(),
            ],
            'delete' => [],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        /** @var CategoryMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(CategoryMutationsModule::class)->getConfiguration();
        $usePayloadableCategoryMutations = $moduleConfiguration->usePayloadableCategoryMutations();
        return match ($fieldName) {
            'update' => $usePayloadableCategoryMutations
                ? $this->getPayloadableUpdateGenericCategoryTermMutationResolver()
                : $this->getUpdateGenericCategoryTermMutationResolver(),
            'delete' => $usePayloadableCategoryMutations
                ? $this->getPayloadableDeleteGenericCategoryTermMutationResolver()
                : $this->getDeleteGenericCategoryTermMutationResolver(),
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
                'update' => $this->getGenericCategoryObjectTypeResolver(),
                'delete' => $this->getBooleanScalarTypeResolver(),
                default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'update' => $this->getGenericCategoryUpdateMutationPayloadObjectTypeResolver(),
            'delete' => $this->getGenericCategoryDeleteMutationPayloadObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
