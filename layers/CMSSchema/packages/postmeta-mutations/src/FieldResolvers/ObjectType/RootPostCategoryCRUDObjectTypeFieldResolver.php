<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPCMSSchema\CustomPostMetaMutations\FieldResolvers\ObjectType\AbstractRootCustomPostCRUDObjectTypeFieldResolver;
use PoPCMSSchema\CustomPostMetaMutations\Module;
use PoPCMSSchema\CustomPostMetaMutations\ModuleConfiguration;
use PoPCMSSchema\PostMetaMutations\TypeResolvers\ObjectType\RootAddPostTermMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostMetaMutations\TypeResolvers\ObjectType\RootDeletePostTermMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostMetaMutations\TypeResolvers\ObjectType\RootSetPostTermMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostMetaMutations\TypeResolvers\ObjectType\RootUpdatePostTermMetaMutationPayloadObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Root\App;

class RootPostCRUDObjectTypeFieldResolver extends AbstractRootCustomPostCRUDObjectTypeFieldResolver
{
    private ?PostObjectTypeResolver $postCustomPostObjectTypeResolver = null;
    private ?RootDeletePostTermMetaMutationPayloadObjectTypeResolver $rootDeletePostTermMetaMutationPayloadObjectTypeResolver = null;
    private ?RootSetPostTermMetaMutationPayloadObjectTypeResolver $rootSetPostTermMetaMutationPayloadObjectTypeResolver = null;
    private ?RootUpdatePostTermMetaMutationPayloadObjectTypeResolver $rootUpdatePostTermMetaMutationPayloadObjectTypeResolver = null;
    private ?RootAddPostTermMetaMutationPayloadObjectTypeResolver $rootAddPostTermMetaMutationPayloadObjectTypeResolver = null;

    final protected function getPostObjectTypeResolver(): PostObjectTypeResolver
    {
        if ($this->postCustomPostObjectTypeResolver === null) {
            /** @var PostObjectTypeResolver */
            $postCustomPostObjectTypeResolver = $this->instanceManager->getInstance(PostObjectTypeResolver::class);
            $this->postCustomPostObjectTypeResolver = $postCustomPostObjectTypeResolver;
        }
        return $this->postCustomPostObjectTypeResolver;
    }
    final protected function getRootDeletePostTermMetaMutationPayloadObjectTypeResolver(): RootDeletePostTermMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootDeletePostTermMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootDeletePostTermMetaMutationPayloadObjectTypeResolver */
            $rootDeletePostTermMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootDeletePostTermMetaMutationPayloadObjectTypeResolver::class);
            $this->rootDeletePostTermMetaMutationPayloadObjectTypeResolver = $rootDeletePostTermMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootDeletePostTermMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootSetPostTermMetaMutationPayloadObjectTypeResolver(): RootSetPostTermMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootSetPostTermMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootSetPostTermMetaMutationPayloadObjectTypeResolver */
            $rootSetPostTermMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootSetPostTermMetaMutationPayloadObjectTypeResolver::class);
            $this->rootSetPostTermMetaMutationPayloadObjectTypeResolver = $rootSetPostTermMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootSetPostTermMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootUpdatePostTermMetaMutationPayloadObjectTypeResolver(): RootUpdatePostTermMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootUpdatePostTermMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootUpdatePostTermMetaMutationPayloadObjectTypeResolver */
            $rootUpdatePostTermMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootUpdatePostTermMetaMutationPayloadObjectTypeResolver::class);
            $this->rootUpdatePostTermMetaMutationPayloadObjectTypeResolver = $rootUpdatePostTermMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootUpdatePostTermMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootAddPostTermMetaMutationPayloadObjectTypeResolver(): RootAddPostTermMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootAddPostTermMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootAddPostTermMetaMutationPayloadObjectTypeResolver */
            $rootAddPostTermMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootAddPostTermMetaMutationPayloadObjectTypeResolver::class);
            $this->rootAddPostTermMetaMutationPayloadObjectTypeResolver = $rootAddPostTermMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootAddPostTermMetaMutationPayloadObjectTypeResolver;
    }

    protected function getCustomPostEntityName(): string
    {
        return 'Post';
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        $customPostEntityName = $this->getCustomPostEntityName();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCustomPostMetaMutations = $moduleConfiguration->usePayloadableCustomPostMetaMutations();
        if ($usePayloadableCustomPostMetaMutations) {
            return match ($fieldName) {
                'add' . $customPostEntityName . 'Meta',
                'add' . $customPostEntityName . 'Metas',
                'add' . $customPostEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootAddPostTermMetaMutationPayloadObjectTypeResolver(),
                'update' . $customPostEntityName . 'Meta',
                'update' . $customPostEntityName . 'Metas',
                'update' . $customPostEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootUpdatePostTermMetaMutationPayloadObjectTypeResolver(),
                'delete' . $customPostEntityName . 'Meta',
                'delete' . $customPostEntityName . 'Metas',
                'delete' . $customPostEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootDeletePostTermMetaMutationPayloadObjectTypeResolver(),
                'set' . $customPostEntityName . 'Meta',
                'set' . $customPostEntityName . 'Metas',
                'set' . $customPostEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootSetPostTermMetaMutationPayloadObjectTypeResolver(),
                default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'add' . $customPostEntityName . 'Meta',
            'add' . $customPostEntityName . 'Metas',
            'update' . $customPostEntityName . 'Meta',
            'update' . $customPostEntityName . 'Metas',
            'delete' . $customPostEntityName . 'Meta',
            'delete' . $customPostEntityName . 'Metas',
            'set' . $customPostEntityName . 'Meta',
            'set' . $customPostEntityName . 'Metas'
                => $this->getPostObjectTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
