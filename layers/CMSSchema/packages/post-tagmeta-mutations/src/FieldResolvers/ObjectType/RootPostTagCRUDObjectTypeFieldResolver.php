<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;
use PoPCMSSchema\TagMetaMutations\FieldResolvers\ObjectType\AbstractRootTagCRUDObjectTypeFieldResolver;
use PoPCMSSchema\TagMetaMutations\Module;
use PoPCMSSchema\TagMetaMutations\ModuleConfiguration;
use PoPCMSSchema\PostTagMetaMutations\TypeResolvers\ObjectType\RootAddPostTagTermMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostTagMetaMutations\TypeResolvers\ObjectType\RootDeletePostTagTermMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostTagMetaMutations\TypeResolvers\ObjectType\RootSetPostTagTermMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostTagMetaMutations\TypeResolvers\ObjectType\RootUpdatePostTagTermMetaMutationPayloadObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Root\App;

class RootPostTagCRUDObjectTypeFieldResolver extends AbstractRootTagCRUDObjectTypeFieldResolver
{
    private ?PostTagObjectTypeResolver $postTagObjectTypeResolver = null;
    private ?RootDeletePostTagTermMetaMutationPayloadObjectTypeResolver $rootDeletePostTagTermMetaMutationPayloadObjectTypeResolver = null;
    private ?RootSetPostTagTermMetaMutationPayloadObjectTypeResolver $rootSetPostTagTermMetaMutationPayloadObjectTypeResolver = null;
    private ?RootUpdatePostTagTermMetaMutationPayloadObjectTypeResolver $rootUpdatePostTagTermMetaMutationPayloadObjectTypeResolver = null;
    private ?RootAddPostTagTermMetaMutationPayloadObjectTypeResolver $rootAddPostTagTermMetaMutationPayloadObjectTypeResolver = null;

    final protected function getPostTagObjectTypeResolver(): PostTagObjectTypeResolver
    {
        if ($this->postTagObjectTypeResolver === null) {
            /** @var PostTagObjectTypeResolver */
            $postTagObjectTypeResolver = $this->instanceManager->getInstance(PostTagObjectTypeResolver::class);
            $this->postTagObjectTypeResolver = $postTagObjectTypeResolver;
        }
        return $this->postTagObjectTypeResolver;
    }
    final protected function getRootDeletePostTagTermMetaMutationPayloadObjectTypeResolver(): RootDeletePostTagTermMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootDeletePostTagTermMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootDeletePostTagTermMetaMutationPayloadObjectTypeResolver */
            $rootDeletePostTagTermMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootDeletePostTagTermMetaMutationPayloadObjectTypeResolver::class);
            $this->rootDeletePostTagTermMetaMutationPayloadObjectTypeResolver = $rootDeletePostTagTermMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootDeletePostTagTermMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootSetPostTagTermMetaMutationPayloadObjectTypeResolver(): RootSetPostTagTermMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootSetPostTagTermMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootSetPostTagTermMetaMutationPayloadObjectTypeResolver */
            $rootSetPostTagTermMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootSetPostTagTermMetaMutationPayloadObjectTypeResolver::class);
            $this->rootSetPostTagTermMetaMutationPayloadObjectTypeResolver = $rootSetPostTagTermMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootSetPostTagTermMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootUpdatePostTagTermMetaMutationPayloadObjectTypeResolver(): RootUpdatePostTagTermMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootUpdatePostTagTermMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootUpdatePostTagTermMetaMutationPayloadObjectTypeResolver */
            $rootUpdatePostTagTermMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootUpdatePostTagTermMetaMutationPayloadObjectTypeResolver::class);
            $this->rootUpdatePostTagTermMetaMutationPayloadObjectTypeResolver = $rootUpdatePostTagTermMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootUpdatePostTagTermMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getRootAddPostTagTermMetaMutationPayloadObjectTypeResolver(): RootAddPostTagTermMetaMutationPayloadObjectTypeResolver
    {
        if ($this->rootAddPostTagTermMetaMutationPayloadObjectTypeResolver === null) {
            /** @var RootAddPostTagTermMetaMutationPayloadObjectTypeResolver */
            $rootAddPostTagTermMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootAddPostTagTermMetaMutationPayloadObjectTypeResolver::class);
            $this->rootAddPostTagTermMetaMutationPayloadObjectTypeResolver = $rootAddPostTagTermMetaMutationPayloadObjectTypeResolver;
        }
        return $this->rootAddPostTagTermMetaMutationPayloadObjectTypeResolver;
    }

    protected function getTagEntityName(): string
    {
        return 'PostTag';
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
                    => $this->getRootAddPostTagTermMetaMutationPayloadObjectTypeResolver(),
                'update' . $tagEntityName . 'Meta',
                'update' . $tagEntityName . 'Metas',
                'update' . $tagEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootUpdatePostTagTermMetaMutationPayloadObjectTypeResolver(),
                'delete' . $tagEntityName . 'Meta',
                'delete' . $tagEntityName . 'Metas',
                'delete' . $tagEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootDeletePostTagTermMetaMutationPayloadObjectTypeResolver(),
                'set' . $tagEntityName . 'Meta',
                'set' . $tagEntityName . 'Metas',
                'set' . $tagEntityName . 'MetaMutationPayloadObjects'
                    => $this->getRootSetPostTagTermMetaMutationPayloadObjectTypeResolver(),
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
                => $this->getPostTagObjectTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
