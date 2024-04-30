<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\FieldResolvers\ObjectType\AbstractCustomPostObjectTypeFieldResolver;
use PoPCMSSchema\CustomPostMutations\Module as CustomPostMutationsModule;
use PoPCMSSchema\CustomPostMutations\ModuleConfiguration as CustomPostMutationsModuleConfiguration;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\GenericCustomPostObjectTypeResolver;
use PoPCMSSchema\CustomPostMutations\MutationResolvers\PayloadableUpdateGenericCustomPostMutationResolver;
use PoPCMSSchema\CustomPostMutations\MutationResolvers\UpdateGenericCustomPostMutationResolver;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\CustomPostUpdateInputObjectTypeResolver;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType\GenericCustomPostUpdateMutationPayloadObjectTypeResolver;
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
    private ?CustomPostUpdateInputObjectTypeResolver $customPostUpdateInputObjectTypeResolver = null;

    final public function setGenericCustomPostObjectTypeResolver(GenericCustomPostObjectTypeResolver $genericCustomPostObjectTypeResolver): void
    {
        $this->genericCustomPostObjectTypeResolver = $genericCustomPostObjectTypeResolver;
    }
    final protected function getGenericCustomPostObjectTypeResolver(): GenericCustomPostObjectTypeResolver
    {
        if ($this->genericCustomPostObjectTypeResolver === null) {
            /** @var GenericCustomPostObjectTypeResolver */
            $genericCustomPostObjectTypeResolver = $this->instanceManager->getInstance(GenericCustomPostObjectTypeResolver::class);
            $this->genericCustomPostObjectTypeResolver = $genericCustomPostObjectTypeResolver;
        }
        return $this->genericCustomPostObjectTypeResolver;
    }
    final public function setGenericCustomPostUpdateMutationPayloadObjectTypeResolver(GenericCustomPostUpdateMutationPayloadObjectTypeResolver $genericCustomPostUpdateMutationPayloadObjectTypeResolver): void
    {
        $this->genericCustomPostUpdateMutationPayloadObjectTypeResolver = $genericCustomPostUpdateMutationPayloadObjectTypeResolver;
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
    final public function setUpdateGenericCustomPostMutationResolver(UpdateGenericCustomPostMutationResolver $updateGenericCustomPostMutationResolver): void
    {
        $this->updateGenericCustomPostMutationResolver = $updateGenericCustomPostMutationResolver;
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
    final public function setPayloadableUpdateGenericCustomPostMutationResolver(PayloadableUpdateGenericCustomPostMutationResolver $payloadableUpdateGenericCustomPostMutationResolver): void
    {
        $this->payloadableUpdateGenericCustomPostMutationResolver = $payloadableUpdateGenericCustomPostMutationResolver;
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
    final public function setGenericCustomPostUpdateInputObjectTypeResolver(CustomPostUpdateInputObjectTypeResolver $customPostUpdateInputObjectTypeResolver): void
    {
        $this->customPostUpdateInputObjectTypeResolver = $customPostUpdateInputObjectTypeResolver;
    }
    final protected function getGenericCustomPostUpdateInputObjectTypeResolver(): CustomPostUpdateInputObjectTypeResolver
    {
        if ($this->customPostUpdateInputObjectTypeResolver === null) {
            /** @var CustomPostUpdateInputObjectTypeResolver */
            $customPostUpdateInputObjectTypeResolver = $this->instanceManager->getInstance(CustomPostUpdateInputObjectTypeResolver::class);
            $this->customPostUpdateInputObjectTypeResolver = $customPostUpdateInputObjectTypeResolver;
        }
        return $this->customPostUpdateInputObjectTypeResolver;
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

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'update' => $this->__('Update the post', 'post-mutations'),
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
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
