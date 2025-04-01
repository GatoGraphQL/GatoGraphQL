<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\Comments\TypeResolvers\ObjectType\GenericCommentObjectTypeResolver;
use PoPCMSSchema\CommentMetaMutations\FieldResolvers\ObjectType\AbstractRootCommentCRUDObjectTypeFieldResolver;
use PoPCMSSchema\CommentMetaMutations\Module;
use PoPCMSSchema\CommentMetaMutations\ModuleConfiguration;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\ObjectType\RootAddGenericCommentMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\ObjectType\RootDeleteGenericCommentMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\ObjectType\RootSetGenericCommentMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\ObjectType\RootUpdateGenericCommentMetaMutationPayloadObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Root\App;

class RootGenericCommentCRUDObjectTypeFieldResolver extends AbstractRootCommentCRUDObjectTypeFieldResolver
{
    private ?GenericCommentObjectTypeResolver $genericCommentObjectTypeResolver = null;
    private ?RootDeleteGenericCommentMetaMutationPayloadObjectTypeResolver $rootDeleteGenericCommentMetaMutationPayloadObjectTypeResolver = null;
    private ?RootSetGenericCommentMetaMutationPayloadObjectTypeResolver $rootSetGenericCommentMetaMutationPayloadObjectTypeResolver = null;
    private ?RootUpdateGenericCommentMetaMutationPayloadObjectTypeResolver $rootUpdateGenericCommentMetaMutationPayloadObjectTypeResolver = null;
    private ?RootAddGenericCommentMetaMutationPayloadObjectTypeResolver $rootAddGenericCommentMetaMutationPayloadObjectTypeResolver = null;

    final protected function getGenericCommentObjectTypeResolver(): GenericCommentObjectTypeResolver
    {
        if ($this->genericCommentObjectTypeResolver === null) {
            /** @var GenericCommentObjectTypeResolver */
            $genericCommentObjectTypeResolver = $this->instanceManager->getInstance(GenericCommentObjectTypeResolver::class);
            $this->genericCommentObjectTypeResolver = $genericCommentObjectTypeResolver;
        }
        return $this->genericCommentObjectTypeResolver;
    }
    final protected function getRootDeleteGenericCommentMetaMutationPayloadObjectTypeResolver(): RootDeleteGenericCommentMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootDeleteGenericCommentMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootDeleteGenericCommentMetaMutationPayloadObjectTypeResolver */
            $rootDeleteGenericCommentMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootDeleteGenericCommentMetaMutationPayloadObjectTypeResolver::class);
            $this->rootDeleteGenericCommentMetaMutationPayloadObjectTypeResolver = $rootDeleteGenericCommentMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootDeleteGenericCommentMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootSetGenericCommentMetaMutationPayloadObjectTypeResolver(): RootSetGenericCommentMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootSetGenericCommentMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootSetGenericCommentMetaMutationPayloadObjectTypeResolver */
            $rootSetGenericCommentMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootSetGenericCommentMetaMutationPayloadObjectTypeResolver::class);
            $this->rootSetGenericCommentMetaMutationPayloadObjectTypeResolver = $rootSetGenericCommentMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootSetGenericCommentMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootUpdateGenericCommentMetaMutationPayloadObjectTypeResolver(): RootUpdateGenericCommentMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootUpdateGenericCommentMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootUpdateGenericCommentMetaMutationPayloadObjectTypeResolver */
            $rootUpdateGenericCommentMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootUpdateGenericCommentMetaMutationPayloadObjectTypeResolver::class);
            $this->rootUpdateGenericCommentMetaMutationPayloadObjectTypeResolver = $rootUpdateGenericCommentMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootUpdateGenericCommentMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootAddGenericCommentMetaMutationPayloadObjectTypeResolver(): RootAddGenericCommentMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootAddGenericCommentMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootAddGenericCommentMetaMutationPayloadObjectTypeResolver */
            $rootAddGenericCommentMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootAddGenericCommentMetaMutationPayloadObjectTypeResolver::class);
            $this->rootAddGenericCommentMetaMutationPayloadObjectTypeResolver = $rootAddGenericCommentMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootAddGenericCommentMetaMutationPayloadObjectTypeResolver;
    }

    protected function getCommentEntityName(): string
    {
        return 'Comment';
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        $commentEntityName = $this->getCommentEntityName();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCommentMetaMutations = $moduleConfiguration->usePayloadableCommentMetaMutations();
        if ($usePayloadableCommentMetaMutations) {
            return match ($fieldName) {
                'add' . $commentEntityName . 'Meta',
                'add' . $commentEntityName . 'Metas',
                'add' . $commentEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootAddGenericCommentMetaMutationPayloadObjectTypeResolver(),
                'update' . $commentEntityName . 'Meta',
                'update' . $commentEntityName . 'Metas',
                'update' . $commentEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootUpdateGenericCommentMetaMutationPayloadObjectTypeResolver(),
                'delete' . $commentEntityName . 'Meta',
                'delete' . $commentEntityName . 'Metas',
                'delete' . $commentEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootDeleteGenericCommentMetaMutationPayloadObjectTypeResolver(),
                'set' . $commentEntityName . 'Meta',
                'set' . $commentEntityName . 'Metas',
                'set' . $commentEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootSetGenericCommentMetaMutationPayloadObjectTypeResolver(),
                default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'add' . $commentEntityName . 'Meta',
            'add' . $commentEntityName . 'Metas',
            'update' . $commentEntityName . 'Meta',
            'update' . $commentEntityName . 'Metas',
            'delete' . $commentEntityName . 'Meta',
            'delete' . $commentEntityName . 'Metas',
            'set' . $commentEntityName . 'Meta',
            'set' . $commentEntityName . 'Metas'
                => $this->getGenericCommentObjectTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
