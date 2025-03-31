<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\Tags\TypeResolvers\ObjectType\GenericTagObjectTypeResolver;
use PoPCMSSchema\TagMetaMutations\FieldResolvers\ObjectType\AbstractRootTagCRUDObjectTypeFieldResolver;
use PoPCMSSchema\TagMetaMutations\Module;
use PoPCMSSchema\TagMetaMutations\ModuleConfiguration;
use PoPCMSSchema\CustomPostTagMetaMutations\TypeResolvers\ObjectType\RootAddGenericTagTermMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostTagMetaMutations\TypeResolvers\ObjectType\RootDeleteGenericTagTermMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostTagMetaMutations\TypeResolvers\ObjectType\RootSetGenericTagTermMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostTagMetaMutations\TypeResolvers\ObjectType\RootUpdateGenericTagTermMetaMutationPayloadObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Root\App;

class RootGenericTagCRUDObjectTypeFieldResolver extends AbstractRootTagCRUDObjectTypeFieldResolver
{
    private ?GenericTagObjectTypeResolver $genericTagObjectTypeResolver = null;
    private ?RootDeleteGenericTagTermMetaMutationPayloadObjectTypeResolver $rootDeleteGenericTagTermMetaMutationPayloadObjectTypeResolver = null;
    private ?RootSetGenericTagTermMetaMutationPayloadObjectTypeResolver $rootSetGenericTagTermMetaMutationPayloadObjectTypeResolver = null;
    private ?RootUpdateGenericTagTermMetaMutationPayloadObjectTypeResolver $rootUpdateGenericTagTermMetaMutationPayloadObjectTypeResolver = null;
    private ?RootAddGenericTagTermMetaMutationPayloadObjectTypeResolver $rootAddGenericTagTermMetaMutationPayloadObjectTypeResolver = null;

    final protected function getGenericTagObjectTypeResolver(): GenericTagObjectTypeResolver
    {
        if ($this->genericTagObjectTypeResolver === null) {
            /** @var GenericTagObjectTypeResolver */
            $genericTagObjectTypeResolver = $this->instanceManager->getInstance(GenericTagObjectTypeResolver::class);
            $this->genericTagObjectTypeResolver = $genericTagObjectTypeResolver;
        }
        return $this->genericTagObjectTypeResolver;
    }
    final protected function getRootDeleteGenericTagTermMetaMutationPayloadObjectTypeResolver(): RootDeleteGenericTagTermMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootDeleteGenericTagTermMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootDeleteGenericTagTermMetaMutationPayloadObjectTypeResolver */
            $rootDeleteGenericTagTermMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootDeleteGenericTagTermMetaMutationPayloadObjectTypeResolver::class);
            $this->rootDeleteGenericTagTermMetaMutationPayloadObjectTypeResolver = $rootDeleteGenericTagTermMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootDeleteGenericTagTermMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootSetGenericTagTermMetaMutationPayloadObjectTypeResolver(): RootSetGenericTagTermMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootSetGenericTagTermMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootSetGenericTagTermMetaMutationPayloadObjectTypeResolver */
            $rootSetGenericTagTermMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootSetGenericTagTermMetaMutationPayloadObjectTypeResolver::class);
            $this->rootSetGenericTagTermMetaMutationPayloadObjectTypeResolver = $rootSetGenericTagTermMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootSetGenericTagTermMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootUpdateGenericTagTermMetaMutationPayloadObjectTypeResolver(): RootUpdateGenericTagTermMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootUpdateGenericTagTermMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootUpdateGenericTagTermMetaMutationPayloadObjectTypeResolver */
            $rootUpdateGenericTagTermMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootUpdateGenericTagTermMetaMutationPayloadObjectTypeResolver::class);
            $this->rootUpdateGenericTagTermMetaMutationPayloadObjectTypeResolver = $rootUpdateGenericTagTermMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootUpdateGenericTagTermMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootAddGenericTagTermMetaMutationPayloadObjectTypeResolver(): RootAddGenericTagTermMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootAddGenericTagTermMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootAddGenericTagTermMetaMutationPayloadObjectTypeResolver */
            $rootAddGenericTagTermMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootAddGenericTagTermMetaMutationPayloadObjectTypeResolver::class);
            $this->rootAddGenericTagTermMetaMutationPayloadObjectTypeResolver = $rootAddGenericTagTermMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootAddGenericTagTermMetaMutationPayloadObjectTypeResolver;
    }

    protected function getTagEntityName(): string
    {
        return 'Tag';
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        $tagEntityName = $this->getTagEntityName();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableTagMetaMutations = $moduleConfiguration->usePayloadableTagMetaMutations();
        if ($usePayloadableTagMetaMutations) {
            return match ($fieldName) {
                'add' . $tagEntityName . 'Meta',
                'add' . $tagEntityName . 'Metas',
                'add' . $tagEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootAddGenericTagTermMetaMutationPayloadObjectTypeResolver(),
                'update' . $tagEntityName . 'Meta',
                'update' . $tagEntityName . 'Metas',
                'update' . $tagEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootUpdateGenericTagTermMetaMutationPayloadObjectTypeResolver(),
                'delete' . $tagEntityName . 'Meta',
                'delete' . $tagEntityName . 'Metas',
                'delete' . $tagEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootDeleteGenericTagTermMetaMutationPayloadObjectTypeResolver(),
                'set' . $tagEntityName . 'Meta',
                'set' . $tagEntityName . 'Metas',
                'set' . $tagEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootSetGenericTagTermMetaMutationPayloadObjectTypeResolver(),
                default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'add' . $tagEntityName . 'Meta',
            'add' . $tagEntityName . 'Metas',
            'update' . $tagEntityName . 'Meta',
            'update' . $tagEntityName . 'Metas',
            'delete' . $tagEntityName . 'Meta',
            'delete' . $tagEntityName . 'Metas',
            'set' . $tagEntityName . 'Meta',
            'set' . $tagEntityName . 'Metas'
                => $this->getGenericTagObjectTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
