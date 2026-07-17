<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\FieldResolvers\ObjectType\AbstractCustomPostObjectTypeFieldResolver;
use PoPCMSSchema\CustomPostMutations\Module as CustomPostMutationsModule;
use PoPCMSSchema\CustomPostMutations\ModuleConfiguration as CustomPostMutationsModuleConfiguration;
use PoPCMSSchema\CustomPostMutations\MutationResolvers\PayloadableUpdateGenericCustomPostMutationResolver;
use PoPCMSSchema\CustomPostMutations\MutationResolvers\UpdateGenericCustomPostMutationResolver;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\AbstractCustomPostUpdateInputObjectTypeResolver;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\GenericCustomPostUpdateInputObjectTypeResolver;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType\GenericCustomPostUpdateMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\GenericCustomPostObjectTypeResolver;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\AbstractDeleteCustomPostInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericCustomPostObjectTypeFieldResolver extends AbstractCustomPostObjectTypeFieldResolver
{
    private ?GenericCustomPostObjectTypeResolver $genericCustomPostObjectTypeResolver = null;
    private ?GenericCustomPostUpdateMutationPayloadObjectTypeResolver $genericCustomPostUpdateMutationPayloadObjectTypeResolver = null;
    private ?UpdateGenericCustomPostMutationResolver $updateGenericCustomPostMutationResolver = null;
    private ?PayloadableUpdateGenericCustomPostMutationResolver $payloadableUpdateGenericCustomPostMutationResolver = null;
    private ?GenericCustomPostUpdateInputObjectTypeResolver $genericCustomPostUpdateInputObjectTypeResolver = null;

    private ?GenericCustomPostDeleteMutationPayloadObjectTypeResolver $genericCustomPostDeleteMutationPayloadObjectTypeResolver = null;
    private ?DeleteGenericCustomPostMutationResolver $deleteGenericCustomPostMutationResolver = null;
    private ?PayloadableDeleteGenericCustomPostMutationResolver $payloadableDeleteGenericCustomPostMutationResolver = null;
    private ?GenericCustomPostDeleteInputObjectTypeResolver $genericCustomPostDeleteInputObjectTypeResolver = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    final protected function getGenericCustomPostObjectTypeResolver(): GenericCustomPostObjectTypeResolver
    {
        if ($this->genericCustomPostObjectTypeResolver === null) {
            /** @var GenericCustomPostObjectTypeResolver */
            $genericCustomPostObjectTypeResolver = $this->instanceManager->getInstance(GenericCustomPostObjectTypeResolver::class);
            $this->genericCustomPostObjectTypeResolver = $genericCustomPostObjectTypeResolver;
        }
        return $this->genericCustomPostObjectTypeResolver;
    }
    final protected function getGenericCustomPostUpdateMutationPayloadObjectTypeResolver(): GenericCustomPostUpdateMutationPayloadObjectTypeResolver
    {
        if ($this->genericCustomPostUpdateMutationPayloadObjectTypeResolver === null) {
            /** @var GenericCustomPostUpdateMutationPayloadObjectTypeResolver */
            $genericCustomPostUpdateMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(GenericCustomPostUpdateMutationPayloadObjectTypeResolver::class);
            $this->genericCustomPostUpdateMutationPayloadObjectTypeResolver = $genericCustomPostUpdateMutationPayloadObjectTypeResolver;
        }
        return $this->genericCustomPostUpdateMutationPayloadObjectTypeResolver;
    }
    final protected function getUpdateGenericCustomPostMutationResolver(): UpdateGenericCustomPostMutationResolver
    {
        if ($this->updateGenericCustomPostMutationResolver === null) {
            /** @var UpdateGenericCustomPostMutationResolver */
            $updateGenericCustomPostMutationResolver = $this->instanceManager->getInstance(UpdateGenericCustomPostMutationResolver::class);
            $this->updateGenericCustomPostMutationResolver = $updateGenericCustomPostMutationResolver;
        }
        return $this->updateGenericCustomPostMutationResolver;
    }
    final protected function getPayloadableUpdateGenericCustomPostMutationResolver(): PayloadableUpdateGenericCustomPostMutationResolver
    {
        if ($this->payloadableUpdateGenericCustomPostMutationResolver === null) {
            /** @var PayloadableUpdateGenericCustomPostMutationResolver */
            $payloadableUpdateGenericCustomPostMutationResolver = $this->instanceManager->getInstance(PayloadableUpdateGenericCustomPostMutationResolver::class);
            $this->payloadableUpdateGenericCustomPostMutationResolver = $payloadableUpdateGenericCustomPostMutationResolver;
        }
        return $this->payloadableUpdateGenericCustomPostMutationResolver;
    }
    final protected function getGenericCustomPostUpdateInputObjectTypeResolver(): GenericCustomPostUpdateInputObjectTypeResolver
    {
        if ($this->genericCustomPostUpdateInputObjectTypeResolver === null) {
            /** @var GenericCustomPostUpdateInputObjectTypeResolver */
            $genericCustomPostUpdateInputObjectTypeResolver = $this->instanceManager->getInstance(GenericCustomPostUpdateInputObjectTypeResolver::class);
            $this->genericCustomPostUpdateInputObjectTypeResolver = $genericCustomPostUpdateInputObjectTypeResolver;
        }
        return $this->genericCustomPostUpdateInputObjectTypeResolver;
    }

    final protected function getGenericCustomPostDeleteMutationPayloadObjectTypeResolver(): GenericCustomPostDeleteMutationPayloadObjectTypeResolver
    {
        if ($this->genericCustomPostDeleteMutationPayloadObjectTypeResolver === null) {
            /** @var GenericCustomPostDeleteMutationPayloadObjectTypeResolver */
            $genericCustomPostDeleteMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(GenericCustomPostDeleteMutationPayloadObjectTypeResolver::class);
            $this->genericCustomPostDeleteMutationPayloadObjectTypeResolver = $genericCustomPostDeleteMutationPayloadObjectTypeResolver;
        }
        return $this->genericCustomPostDeleteMutationPayloadObjectTypeResolver;
    }
    final protected function getDeleteGenericCustomPostMutationResolver(): DeleteGenericCustomPostMutationResolver
    {
        if ($this->deleteGenericCustomPostMutationResolver === null) {
            /** @var DeleteGenericCustomPostMutationResolver */
            $deleteGenericCustomPostMutationResolver = $this->instanceManager->getInstance(DeleteGenericCustomPostMutationResolver::class);
            $this->deleteGenericCustomPostMutationResolver = $deleteGenericCustomPostMutationResolver;
        }
        return $this->deleteGenericCustomPostMutationResolver;
    }
    final protected function getPayloadableDeleteGenericCustomPostMutationResolver(): PayloadableDeleteGenericCustomPostMutationResolver
    {
        if ($this->payloadableDeleteGenericCustomPostMutationResolver === null) {
            /** @var PayloadableDeleteGenericCustomPostMutationResolver */
            $payloadableDeleteGenericCustomPostMutationResolver = $this->instanceManager->getInstance(PayloadableDeleteGenericCustomPostMutationResolver::class);
            $this->payloadableDeleteGenericCustomPostMutationResolver = $payloadableDeleteGenericCustomPostMutationResolver;
        }
        return $this->payloadableDeleteGenericCustomPostMutationResolver;
    }
    final protected function getGenericCustomPostDeleteInputObjectTypeResolver(): GenericCustomPostDeleteInputObjectTypeResolver
    {
        if ($this->genericCustomPostDeleteInputObjectTypeResolver === null) {
            /** @var GenericCustomPostDeleteInputObjectTypeResolver */
            $genericCustomPostDeleteInputObjectTypeResolver = $this->instanceManager->getInstance(GenericCustomPostDeleteInputObjectTypeResolver::class);
            $this->genericCustomPostDeleteInputObjectTypeResolver = $genericCustomPostDeleteInputObjectTypeResolver;
        }
        return $this->genericCustomPostDeleteInputObjectTypeResolver;
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
            GenericCustomPostObjectTypeResolver::class,
        ];
    }

    protected function getCustomPostUpdateInputObjectTypeResolver(): AbstractCustomPostUpdateInputObjectTypeResolver
    {
        return $this->getGenericCustomPostUpdateInputObjectTypeResolver();
    }

    protected function getCustomPostDeleteInputObjectTypeResolver(): AbstractDeleteCustomPostInputObjectTypeResolver
    {
        return $this->getGenericCustomPostDeleteInputObjectTypeResolver();
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'update' => $this->__('Update the post', 'gatographql'),
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
                'input' => $this->getGenericCustomPostUpdateInputObjectTypeResolver(),
            ],
            'delete' => [
                'input' => $this->getGenericCustomPostDeleteInputObjectTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        /** @var CustomPostMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(CustomPostMutationsModule::class)->getConfiguration();
        $usePayloadableCustomPostMutations = $moduleConfiguration->usePayloadableCustomPostMutations();
        return match ($fieldName) {
            'update' => $usePayloadableCustomPostMutations
                ? $this->getPayloadableUpdateGenericCustomPostMutationResolver()
                : $this->getUpdateGenericCustomPostMutationResolver(),
            'delete' => $usePayloadableCustomPostMutations
                ? $this->getPayloadableDeleteGenericCustomPostMutationResolver()
                : $this->getDeleteGenericCustomPostMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var CustomPostMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(CustomPostMutationsModule::class)->getConfiguration();
        $usePayloadableCustomPostMutations = $moduleConfiguration->usePayloadableCustomPostMutations();
        return match ($fieldName) {
            'update' => $usePayloadableCustomPostMutations
                ? $this->getGenericCustomPostUpdateMutationPayloadObjectTypeResolver()
                : $this->getGenericCustomPostObjectTypeResolver(),
            'delete' => $usePayloadableCustomPostMutations
                ? $this->getGenericCustomPostDeleteMutationPayloadObjectTypeResolver()
                : $this->getBooleanScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
