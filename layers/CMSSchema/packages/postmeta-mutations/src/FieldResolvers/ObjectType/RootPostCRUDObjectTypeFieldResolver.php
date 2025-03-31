<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPCMSSchema\CustomPostMetaMutations\FieldResolvers\ObjectType\AbstractRootCustomPostCRUDObjectTypeFieldResolver;
use PoPCMSSchema\CustomPostMetaMutations\Module;
use PoPCMSSchema\CustomPostMetaMutations\ModuleConfiguration;
use PoPCMSSchema\PostMetaMutations\TypeResolvers\ObjectType\RootAddPostMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostMetaMutations\TypeResolvers\ObjectType\RootDeletePostMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostMetaMutations\TypeResolvers\ObjectType\RootSetPostMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostMetaMutations\TypeResolvers\ObjectType\RootUpdatePostMetaMutationPayloadObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Root\App;

/**
 * Made abstract to not initialize class (it's disabled)
 */
abstract class RootPostCRUDObjectTypeFieldResolver extends AbstractRootCustomPostCRUDObjectTypeFieldResolver
{
    private ?PostObjectTypeResolver $postObjectTypeResolver = null;
    private ?RootDeletePostMetaMutationPayloadObjectTypeResolver $rootDeletePostMetaMutationPayloadObjectTypeResolver = null;
    private ?RootSetPostMetaMutationPayloadObjectTypeResolver $rootSetPostMetaMutationPayloadObjectTypeResolver = null;
    private ?RootUpdatePostMetaMutationPayloadObjectTypeResolver $rootUpdatePostMetaMutationPayloadObjectTypeResolver = null;
    private ?RootAddPostMetaMutationPayloadObjectTypeResolver $rootAddPostMetaMutationPayloadObjectTypeResolver = null;

    final protected function getPostObjectTypeResolver(): PostObjectTypeResolver
    {
        if ($this->postObjectTypeResolver === null) {
            /** @var PostObjectTypeResolver */
            $postObjectTypeResolver = $this->instanceManager->getInstance(PostObjectTypeResolver::class);
            $this->postObjectTypeResolver = $postObjectTypeResolver;
        }
        return $this->postObjectTypeResolver;
    }
    final protected function getRootDeletePostMetaMutationPayloadObjectTypeResolver(): RootDeletePostMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootDeletePostMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootDeletePostMetaMutationPayloadObjectTypeResolver */
            $rootDeletePostMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootDeletePostMetaMutationPayloadObjectTypeResolver::class);
            $this->rootDeletePostMetaMutationPayloadObjectTypeResolver = $rootDeletePostMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootDeletePostMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootSetPostMetaMutationPayloadObjectTypeResolver(): RootSetPostMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootSetPostMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootSetPostMetaMutationPayloadObjectTypeResolver */
            $rootSetPostMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootSetPostMetaMutationPayloadObjectTypeResolver::class);
            $this->rootSetPostMetaMutationPayloadObjectTypeResolver = $rootSetPostMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootSetPostMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootUpdatePostMetaMutationPayloadObjectTypeResolver(): RootUpdatePostMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootUpdatePostMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootUpdatePostMetaMutationPayloadObjectTypeResolver */
            $rootUpdatePostMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootUpdatePostMetaMutationPayloadObjectTypeResolver::class);
            $this->rootUpdatePostMetaMutationPayloadObjectTypeResolver = $rootUpdatePostMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootUpdatePostMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootAddPostMetaMutationPayloadObjectTypeResolver(): RootAddPostMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootAddPostMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootAddPostMetaMutationPayloadObjectTypeResolver */
            $rootAddPostMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootAddPostMetaMutationPayloadObjectTypeResolver::class);
            $this->rootAddPostMetaMutationPayloadObjectTypeResolver = $rootAddPostMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootAddPostMetaMutationPayloadObjectTypeResolver;
    }

    /**
     * Disable because we don't need `addPostMeta` and
     * `addCustomPostMeta`, it's too confusing
     */
    public function isServiceEnabled(): bool
    {
        return false;
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
                    => $this->getRootAddPostMetaMutationPayloadObjectTypeResolver(),
                'update' . $customPostEntityName . 'Meta',
                'update' . $customPostEntityName . 'Metas',
                'update' . $customPostEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootUpdatePostMetaMutationPayloadObjectTypeResolver(),
                'delete' . $customPostEntityName . 'Meta',
                'delete' . $customPostEntityName . 'Metas',
                'delete' . $customPostEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootDeletePostMetaMutationPayloadObjectTypeResolver(),
                'set' . $customPostEntityName . 'Meta',
                'set' . $customPostEntityName . 'Metas',
                'set' . $customPostEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootSetPostMetaMutationPayloadObjectTypeResolver(),
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
