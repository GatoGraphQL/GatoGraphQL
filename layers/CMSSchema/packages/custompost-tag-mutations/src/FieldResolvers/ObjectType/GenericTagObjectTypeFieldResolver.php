<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\TagMutations\FieldResolvers\ObjectType\AbstractTagObjectTypeFieldResolver;
use PoPCMSSchema\TagMutations\Module as TagMutationsModule;
use PoPCMSSchema\TagMutations\ModuleConfiguration as TagMutationsModuleConfiguration;
use PoPCMSSchema\Tags\TypeResolvers\ObjectType\GenericTagObjectTypeResolver;
use PoPCMSSchema\CustomPostTagMutations\MutationResolvers\DeleteGenericTagTermMutationResolver;
use PoPCMSSchema\CustomPostTagMutations\MutationResolvers\PayloadableDeleteGenericTagTermMutationResolver;
use PoPCMSSchema\CustomPostTagMutations\MutationResolvers\PayloadableUpdateGenericTagTermMutationResolver;
use PoPCMSSchema\CustomPostTagMutations\MutationResolvers\UpdateGenericTagTermMutationResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\InputObjectType\GenericTagTermUpdateInputObjectTypeResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\ObjectType\GenericTagDeleteMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\ObjectType\GenericTagUpdateMutationPayloadObjectTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;

class GenericTagObjectTypeFieldResolver extends AbstractTagObjectTypeFieldResolver
{
    private ?GenericTagObjectTypeResolver $genericTagObjectTypeResolver = null;
    private ?GenericTagUpdateMutationPayloadObjectTypeResolver $genericTagUpdateMutationPayloadObjectTypeResolver = null;
    private ?GenericTagDeleteMutationPayloadObjectTypeResolver $genericTagDeleteMutationPayloadObjectTypeResolver = null;
    private ?UpdateGenericTagTermMutationResolver $updateGenericTagTermMutationResolver = null;
    private ?DeleteGenericTagTermMutationResolver $deleteGenericTagTermMutationResolver = null;
    private ?PayloadableUpdateGenericTagTermMutationResolver $payloadableUpdateGenericTagTermMutationResolver = null;
    private ?PayloadableDeleteGenericTagTermMutationResolver $payloadableDeleteGenericTagTermMutationResolver = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?GenericTagTermUpdateInputObjectTypeResolver $genericTagTermUpdateInputObjectTypeResolver = null;

    final protected function getGenericTagObjectTypeResolver(): GenericTagObjectTypeResolver
    {
        if ($this->genericTagObjectTypeResolver === null) {
            /** @var GenericTagObjectTypeResolver */
            $genericTagObjectTypeResolver = $this->instanceManager->getInstance(GenericTagObjectTypeResolver::class);
            $this->genericTagObjectTypeResolver = $genericTagObjectTypeResolver;
        }
        return $this->genericTagObjectTypeResolver;
    }
    final protected function getGenericTagUpdateMutationPayloadObjectTypeResolver(): GenericTagUpdateMutationPayloadObjectTypeResolver
    {
        if ($this->genericTagUpdateMutationPayloadObjectTypeResolver === null) {
            /** @var GenericTagUpdateMutationPayloadObjectTypeResolver */
            $genericTagUpdateMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(GenericTagUpdateMutationPayloadObjectTypeResolver::class);
            $this->genericTagUpdateMutationPayloadObjectTypeResolver = $genericTagUpdateMutationPayloadObjectTypeResolver;
        }
        return $this->genericTagUpdateMutationPayloadObjectTypeResolver;
    }
    final protected function getGenericTagDeleteMutationPayloadObjectTypeResolver(): GenericTagDeleteMutationPayloadObjectTypeResolver
    {
        if ($this->genericTagDeleteMutationPayloadObjectTypeResolver === null) {
            /** @var GenericTagDeleteMutationPayloadObjectTypeResolver */
            $genericTagDeleteMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(GenericTagDeleteMutationPayloadObjectTypeResolver::class);
            $this->genericTagDeleteMutationPayloadObjectTypeResolver = $genericTagDeleteMutationPayloadObjectTypeResolver;
        }
        return $this->genericTagDeleteMutationPayloadObjectTypeResolver;
    }
    final protected function getUpdateGenericTagTermMutationResolver(): UpdateGenericTagTermMutationResolver
    {
        if ($this->updateGenericTagTermMutationResolver === null) {
            /** @var UpdateGenericTagTermMutationResolver */
            $updateGenericTagTermMutationResolver = $this->instanceManager->getInstance(UpdateGenericTagTermMutationResolver::class);
            $this->updateGenericTagTermMutationResolver = $updateGenericTagTermMutationResolver;
        }
        return $this->updateGenericTagTermMutationResolver;
    }
    final protected function getDeleteGenericTagTermMutationResolver(): DeleteGenericTagTermMutationResolver
    {
        if ($this->deleteGenericTagTermMutationResolver === null) {
            /** @var DeleteGenericTagTermMutationResolver */
            $deleteGenericTagTermMutationResolver = $this->instanceManager->getInstance(DeleteGenericTagTermMutationResolver::class);
            $this->deleteGenericTagTermMutationResolver = $deleteGenericTagTermMutationResolver;
        }
        return $this->deleteGenericTagTermMutationResolver;
    }
    final protected function getPayloadableUpdateGenericTagTermMutationResolver(): PayloadableUpdateGenericTagTermMutationResolver
    {
        if ($this->payloadableUpdateGenericTagTermMutationResolver === null) {
            /** @var PayloadableUpdateGenericTagTermMutationResolver */
            $payloadableUpdateGenericTagTermMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateGenericTagTermMutationResolver::class);
            $this->payloadableUpdateGenericTagTermMutationResolver = $payloadableUpdateGenericTagTermMutationResolver;
        }
        return $this->payloadableUpdateGenericTagTermMutationResolver;
    }
    final protected function getPayloadableDeleteGenericTagTermMutationResolver(): PayloadableDeleteGenericTagTermMutationResolver
    {
        if ($this->payloadableDeleteGenericTagTermMutationResolver === null) {
            /** @var PayloadableDeleteGenericTagTermMutationResolver */
            $payloadableDeleteGenericTagTermMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteGenericTagTermMutationResolver::class);
            $this->payloadableDeleteGenericTagTermMutationResolver = $payloadableDeleteGenericTagTermMutationResolver;
        }
        return $this->payloadableDeleteGenericTagTermMutationResolver;
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
    final protected function getGenericTagTermUpdateInputObjectTypeResolver(): GenericTagTermUpdateInputObjectTypeResolver
    {
        if ($this->genericTagTermUpdateInputObjectTypeResolver === null) {
            /** @var GenericTagTermUpdateInputObjectTypeResolver */
            $genericTagTermUpdateInputObjectTypeResolver = $this->instanceManager->getInstance(GenericTagTermUpdateInputObjectTypeResolver::class);
            $this->genericTagTermUpdateInputObjectTypeResolver = $genericTagTermUpdateInputObjectTypeResolver;
        }
        return $this->genericTagTermUpdateInputObjectTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            GenericTagObjectTypeResolver::class,
        ];
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'update' => [
                'input' => $this->getGenericTagTermUpdateInputObjectTypeResolver(),
            ],
            'delete' => [],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        /** @var TagMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(TagMutationsModule::class)->getConfiguration();
        $usePayloadableTagMutations = $moduleConfiguration->usePayloadableTagMutations();
        return match ($fieldName) {
            'update' => $usePayloadableTagMutations
                ? $this->getPayloadableUpdateGenericTagTermMutationResolver()
                : $this->getUpdateGenericTagTermMutationResolver(),
            'delete' => $usePayloadableTagMutations
                ? $this->getPayloadableDeleteGenericTagTermMutationResolver()
                : $this->getDeleteGenericTagTermMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var TagMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(TagMutationsModule::class)->getConfiguration();
        $usePayloadableTagMutations = $moduleConfiguration->usePayloadableTagMutations();
        if (!$usePayloadableTagMutations) {
            return match ($fieldName) {
                'update' => $this->getGenericTagObjectTypeResolver(),
                'delete' => $this->getBooleanScalarTypeResolver(),
                default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'update' => $this->getGenericTagUpdateMutationPayloadObjectTypeResolver(),
            'delete' => $this->getGenericTagDeleteMutationPayloadObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
