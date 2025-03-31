<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMetaMutations\FieldResolvers\ObjectType\AbstractCustomPostObjectTypeFieldResolver;
use PoPCMSSchema\CustomPostMetaMutations\Module as CustomPostMetaMutationsModule;
use PoPCMSSchema\CustomPostMetaMutations\ModuleConfiguration as CustomPostMetaMutationsModuleConfiguration;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\GenericCustomPostObjectTypeResolver;
use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\ObjectType\GenericCustomPostAddMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\ObjectType\GenericCustomPostDeleteMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\ObjectType\GenericCustomPostSetMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\ObjectType\GenericCustomPostUpdateMetaMutationPayloadObjectTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericCustomPostObjectTypeFieldResolver extends AbstractCustomPostObjectTypeFieldResolver
{
    private ?GenericCustomPostObjectTypeResolver $genericCustomPostObjectTypeResolver = null;
    private ?GenericCustomPostDeleteMetaMutationPayloadObjectTypeResolver $genericCustomPostDeleteMetaMutationPayloadObjectTypeResolver = null;
    private ?GenericCustomPostAddMetaMutationPayloadObjectTypeResolver $genericCustomPostCreateMutationPayloadObjectTypeResolver = null;
    private ?GenericCustomPostUpdateMetaMutationPayloadObjectTypeResolver $genericCustomPostUpdateMetaMutationPayloadObjectTypeResolver = null;
    private ?GenericCustomPostSetMetaMutationPayloadObjectTypeResolver $genericCustomPostSetMetaMutationPayloadObjectTypeResolver = null;

    final protected function getGenericCustomPostObjectTypeResolver(): GenericCustomPostObjectTypeResolver
    {
        if ($this->genericCustomPostObjectTypeResolver === null) {
            /** @var GenericCustomPostObjectTypeResolver */
            $genericCustomPostObjectTypeResolver = $this->instanceManager->getInstance(GenericCustomPostObjectTypeResolver::class);
            $this->genericCustomPostObjectTypeResolver = $genericCustomPostObjectTypeResolver;
        }
        return $this->genericCustomPostObjectTypeResolver;
    }
    final protected function getGenericCustomPostDeleteMetaMutationPayloadObjectTypeResolver(): GenericCustomPostDeleteMetaMutationPayloadObjectTypeResolver
    {
        if ($this->genericCustomPostDeleteMetaMutationPayloadObjectTypeResolver === null) {
            /** @var GenericCustomPostDeleteMetaMutationPayloadObjectTypeResolver */
            $genericCustomPostDeleteMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(GenericCustomPostDeleteMetaMutationPayloadObjectTypeResolver::class);
            $this->genericCustomPostDeleteMetaMutationPayloadObjectTypeResolver = $genericCustomPostDeleteMetaMutationPayloadObjectTypeResolver;
        }
        return $this->genericCustomPostDeleteMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getGenericCustomPostAddMetaMutationPayloadObjectTypeResolver(): GenericCustomPostAddMetaMutationPayloadObjectTypeResolver
    {
        if ($this->genericCustomPostCreateMutationPayloadObjectTypeResolver === null) {
            /** @var GenericCustomPostAddMetaMutationPayloadObjectTypeResolver */
            $genericCustomPostCreateMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(GenericCustomPostAddMetaMutationPayloadObjectTypeResolver::class);
            $this->genericCustomPostCreateMutationPayloadObjectTypeResolver = $genericCustomPostCreateMutationPayloadObjectTypeResolver;
        }
        return $this->genericCustomPostCreateMutationPayloadObjectTypeResolver;
    }
    final protected function getGenericCustomPostUpdateMetaMutationPayloadObjectTypeResolver(): GenericCustomPostUpdateMetaMutationPayloadObjectTypeResolver
    {
        if ($this->genericCustomPostUpdateMetaMutationPayloadObjectTypeResolver === null) {
            /** @var GenericCustomPostUpdateMetaMutationPayloadObjectTypeResolver */
            $genericCustomPostUpdateMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(GenericCustomPostUpdateMetaMutationPayloadObjectTypeResolver::class);
            $this->genericCustomPostUpdateMetaMutationPayloadObjectTypeResolver = $genericCustomPostUpdateMetaMutationPayloadObjectTypeResolver;
        }
        return $this->genericCustomPostUpdateMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getGenericCustomPostSetMetaMutationPayloadObjectTypeResolver(): GenericCustomPostSetMetaMutationPayloadObjectTypeResolver
    {
        if ($this->genericCustomPostSetMetaMutationPayloadObjectTypeResolver === null) {
            /** @var GenericCustomPostSetMetaMutationPayloadObjectTypeResolver */
            $genericCustomPostSetMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(GenericCustomPostSetMetaMutationPayloadObjectTypeResolver::class);
            $this->genericCustomPostSetMetaMutationPayloadObjectTypeResolver = $genericCustomPostSetMetaMutationPayloadObjectTypeResolver;
        }
        return $this->genericCustomPostSetMetaMutationPayloadObjectTypeResolver;
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

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var CustomPostMetaMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(CustomPostMetaMutationsModule::class)->getConfiguration();
        $usePayloadableCustomPostMetaMutations = $moduleConfiguration->usePayloadableCustomPostMetaMutations();
        if (!$usePayloadableCustomPostMetaMutations) {
            return match ($fieldName) {
                'addMeta',
                'deleteMeta',
                'setMeta',
                'updateMeta'
                    => $this->getGenericCustomPostObjectTypeResolver(),
                default
                    => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'addMeta' => $this->getGenericCustomPostAddMetaMutationPayloadObjectTypeResolver(),
            'deleteMeta' => $this->getGenericCustomPostDeleteMetaMutationPayloadObjectTypeResolver(),
            'setMeta' => $this->getGenericCustomPostSetMetaMutationPayloadObjectTypeResolver(),
            'updateMeta' => $this->getGenericCustomPostUpdateMetaMutationPayloadObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
