<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\Tags\TypeResolvers\ObjectType\GenericTagObjectTypeResolver;
use PoPCMSSchema\TagMutations\FieldResolvers\ObjectType\AbstractTagObjectTypeFieldResolver;
use PoPCMSSchema\TagMutations\Module as TagMutationsModule;
use PoPCMSSchema\TagMutations\ModuleConfiguration as TagMutationsModuleConfiguration;
use PoPCMSSchema\TagMutations\MutationResolvers\DeleteGenericTagTermMutationResolver;
use PoPCMSSchema\TagMutations\MutationResolvers\PayloadableDeleteGenericTagTermMutationResolver;
use PoPCMSSchema\TagMutations\MutationResolvers\PayloadableUpdateGenericTagTermMutationResolver;
use PoPCMSSchema\TagMutations\MutationResolvers\UpdateGenericTagTermMutationResolver;
use PoPCMSSchema\TagMutations\TypeResolvers\InputObjectType\GenericTagTermUpdateInputObjectTypeResolver;
use PoPCMSSchema\TagMutations\TypeResolvers\ObjectType\GenericTagDeleteMutationPayloadObjectTypeResolver;
use PoPCMSSchema\TagMutations\TypeResolvers\ObjectType\GenericTagUpdateMutationPayloadObjectTypeResolver;
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
    private ?GenericTagTermUpdateInputObjectTypeResolver $genericTagTermUpdateInputObjectTypeResolver = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;

    final public function setGenericTagObjectTypeResolver(GenericTagObjectTypeResolver $genericTagObjectTypeResolver): void
    {
        $this->genericTagObjectTypeResolver = $genericTagObjectTypeResolver;
    }
    final protected function getGenericTagObjectTypeResolver(): GenericTagObjectTypeResolver
    {
        if ($this->genericTagObjectTypeResolver === null) {
            /** @var GenericTagObjectTypeResolver */
            $genericTagObjectTypeResolver = $this->instanceManager->getInstance(GenericTagObjectTypeResolver::class);
            $this->genericTagObjectTypeResolver = $genericTagObjectTypeResolver;
        }
        return $this->genericTagObjectTypeResolver;
    }
    final public function setGenericTagUpdateMutationPayloadObjectTypeResolver(GenericTagUpdateMutationPayloadObjectTypeResolver $genericTagUpdateMutationPayloadObjectTypeResolver): void
    {
        $this->genericTagUpdateMutationPayloadObjectTypeResolver = $genericTagUpdateMutationPayloadObjectTypeResolver;
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
    final public function setGenericTagDeleteMutationPayloadObjectTypeResolver(GenericTagDeleteMutationPayloadObjectTypeResolver $genericTagDeleteMutationPayloadObjectTypeResolver): void
    {
        $this->genericTagDeleteMutationPayloadObjectTypeResolver = $genericTagDeleteMutationPayloadObjectTypeResolver;
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
    final public function setUpdateGenericTagTermMutationResolver(UpdateGenericTagTermMutationResolver $updateGenericTagTermMutationResolver): void
    {
        $this->updateGenericTagTermMutationResolver = $updateGenericTagTermMutationResolver;
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
    final public function setDeleteGenericTagTermMutationResolver(DeleteGenericTagTermMutationResolver $deleteGenericTagTermMutationResolver): void
    {
        $this->deleteGenericTagTermMutationResolver = $deleteGenericTagTermMutationResolver;
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
    final public function setPayloadableUpdateGenericTagTermMutationResolver(PayloadableUpdateGenericTagTermMutationResolver $payloadableUpdateGenericTagTermMutationResolver): void
    {
        $this->payloadableUpdateGenericTagTermMutationResolver = $payloadableUpdateGenericTagTermMutationResolver;
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
    final public function setPayloadableDeleteGenericTagTermMutationResolver(PayloadableDeleteGenericTagTermMutationResolver $payloadableDeleteGenericTagTermMutationResolver): void
    {
        $this->payloadableDeleteGenericTagTermMutationResolver = $payloadableDeleteGenericTagTermMutationResolver;
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
    final public function setGenericTagTermUpdateInputObjectTypeResolver(GenericTagTermUpdateInputObjectTypeResolver $genericTagTermUpdateInputObjectTypeResolver): void
    {
        $this->genericTagTermUpdateInputObjectTypeResolver = $genericTagTermUpdateInputObjectTypeResolver;
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

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            GenericTagObjectTypeResolver::class,
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'update' => $this->__('Update the tag', 'tag-mutations'),
            'delete' => $this->__('Delete the tag', 'tag-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
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
