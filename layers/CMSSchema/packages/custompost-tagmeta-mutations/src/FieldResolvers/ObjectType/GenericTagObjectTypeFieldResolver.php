<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\TagMetaMutations\FieldResolvers\ObjectType\AbstractTagObjectTypeFieldResolver;
use PoPCMSSchema\TagMetaMutations\Module as TagMetaMutationsModule;
use PoPCMSSchema\TagMetaMutations\ModuleConfiguration as TagMetaMutationsModuleConfiguration;
use PoPCMSSchema\Tags\TypeResolvers\ObjectType\GenericTagObjectTypeResolver;
use PoPCMSSchema\CustomPostTagMetaMutations\TypeResolvers\ObjectType\GenericTagAddMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostTagMetaMutations\TypeResolvers\ObjectType\GenericTagDeleteMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostTagMetaMutations\TypeResolvers\ObjectType\GenericTagSetMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostTagMetaMutations\TypeResolvers\ObjectType\GenericTagUpdateMetaMutationPayloadObjectTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericTagObjectTypeFieldResolver extends AbstractTagObjectTypeFieldResolver
{
    private ?GenericTagObjectTypeResolver $genericTagObjectTypeResolver = null;
    private ?GenericTagDeleteMetaMutationPayloadObjectTypeResolver $genericTagDeleteMetaMutationPayloadObjectTypeResolver = null;
    private ?GenericTagAddMetaMutationPayloadObjectTypeResolver $genericTagCreateMutationPayloadObjectTypeResolver = null;
    private ?GenericTagUpdateMetaMutationPayloadObjectTypeResolver $genericTagUpdateMetaMutationPayloadObjectTypeResolver = null;
    private ?GenericTagSetMetaMutationPayloadObjectTypeResolver $genericTagSetMetaMutationPayloadObjectTypeResolver = null;

    final protected function getGenericTagObjectTypeResolver(): GenericTagObjectTypeResolver
    {
        if ($this->genericTagObjectTypeResolver === null) {
            /** @var GenericTagObjectTypeResolver */
            $genericTagObjectTypeResolver = $this->instanceManager->getInstance(GenericTagObjectTypeResolver::class);
            $this->genericTagObjectTypeResolver = $genericTagObjectTypeResolver;
        }
        return $this->genericTagObjectTypeResolver;
    }
    final protected function getGenericTagDeleteMetaMutationPayloadObjectTypeResolver(): GenericTagDeleteMetaMutationPayloadObjectTypeResolver
    {
        if ($this->genericTagDeleteMetaMutationPayloadObjectTypeResolver === null) {
            /** @var GenericTagDeleteMetaMutationPayloadObjectTypeResolver */
            $genericTagDeleteMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(GenericTagDeleteMetaMutationPayloadObjectTypeResolver::class);
            $this->genericTagDeleteMetaMutationPayloadObjectTypeResolver = $genericTagDeleteMetaMutationPayloadObjectTypeResolver;
        }
        return $this->genericTagDeleteMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getGenericTagAddMetaMutationPayloadObjectTypeResolver(): GenericTagAddMetaMutationPayloadObjectTypeResolver
    {
        if ($this->genericTagCreateMutationPayloadObjectTypeResolver === null) {
            /** @var GenericTagAddMetaMutationPayloadObjectTypeResolver */
            $genericTagCreateMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(GenericTagAddMetaMutationPayloadObjectTypeResolver::class);
            $this->genericTagCreateMutationPayloadObjectTypeResolver = $genericTagCreateMutationPayloadObjectTypeResolver;
        }
        return $this->genericTagCreateMutationPayloadObjectTypeResolver;
    }
    final protected function getGenericTagUpdateMetaMutationPayloadObjectTypeResolver(): GenericTagUpdateMetaMutationPayloadObjectTypeResolver
    {
        if ($this->genericTagUpdateMetaMutationPayloadObjectTypeResolver === null) {
            /** @var GenericTagUpdateMetaMutationPayloadObjectTypeResolver */
            $genericTagUpdateMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(GenericTagUpdateMetaMutationPayloadObjectTypeResolver::class);
            $this->genericTagUpdateMetaMutationPayloadObjectTypeResolver = $genericTagUpdateMetaMutationPayloadObjectTypeResolver;
        }
        return $this->genericTagUpdateMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getGenericTagSetMetaMutationPayloadObjectTypeResolver(): GenericTagSetMetaMutationPayloadObjectTypeResolver
    {
        if ($this->genericTagSetMetaMutationPayloadObjectTypeResolver === null) {
            /** @var GenericTagSetMetaMutationPayloadObjectTypeResolver */
            $genericTagSetMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(GenericTagSetMetaMutationPayloadObjectTypeResolver::class);
            $this->genericTagSetMetaMutationPayloadObjectTypeResolver = $genericTagSetMetaMutationPayloadObjectTypeResolver;
        }
        return $this->genericTagSetMetaMutationPayloadObjectTypeResolver;
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

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var TagMetaMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(TagMetaMutationsModule::class)->getConfiguration();
        $usePayloadableTagMetaMutations = $moduleConfiguration->usePayloadableTagMetaMutations();
        if (!$usePayloadableTagMetaMutations) {
            return match ($fieldName) {
                'addMeta',
                'deleteMeta',
                'setMeta',
                'updateMeta'
                    => $this->getGenericTagObjectTypeResolver(),
                default
                    => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'addMeta' => $this->getGenericTagAddMetaMutationPayloadObjectTypeResolver(),
            'deleteMeta' => $this->getGenericTagDeleteMetaMutationPayloadObjectTypeResolver(),
            'setMeta' => $this->getGenericTagSetMetaMutationPayloadObjectTypeResolver(),
            'updateMeta' => $this->getGenericTagUpdateMetaMutationPayloadObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
