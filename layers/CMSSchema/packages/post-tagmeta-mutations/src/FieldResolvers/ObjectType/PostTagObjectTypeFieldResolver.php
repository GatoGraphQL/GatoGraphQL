<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\TagMetaMutations\FieldResolvers\ObjectType\AbstractTagObjectTypeFieldResolver;
use PoPCMSSchema\TagMetaMutations\Module as TagMetaMutationsModule;
use PoPCMSSchema\TagMetaMutations\ModuleConfiguration as TagMetaMutationsModuleConfiguration;
use PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;
use PoPCMSSchema\PostTagMetaMutations\TypeResolvers\ObjectType\PostTagAddMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostTagMetaMutations\TypeResolvers\ObjectType\PostTagDeleteMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostTagMetaMutations\TypeResolvers\ObjectType\PostTagSetMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostTagMetaMutations\TypeResolvers\ObjectType\PostTagUpdateMetaMutationPayloadObjectTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PostTagObjectTypeFieldResolver extends AbstractTagObjectTypeFieldResolver
{
    private ?PostTagObjectTypeResolver $postTagObjectTypeResolver = null;
    private ?PostTagDeleteMetaMutationPayloadObjectTypeResolver $postTagDeleteMetaMutationPayloadObjectTypeResolver = null;
    private ?PostTagAddMetaMutationPayloadObjectTypeResolver $postTagCreateMutationPayloadObjectTypeResolver = null;
    private ?PostTagUpdateMetaMutationPayloadObjectTypeResolver $postTagUpdateMetaMutationPayloadObjectTypeResolver = null;
    private ?PostTagSetMetaMutationPayloadObjectTypeResolver $postTagSetMetaMutationPayloadObjectTypeResolver = null;

    final protected function getPostTagObjectTypeResolver(): PostTagObjectTypeResolver
    {
        if ($this->postTagObjectTypeResolver === null) {
            /** @var PostTagObjectTypeResolver */
            $postTagObjectTypeResolver = $this->instanceManager->getInstance(PostTagObjectTypeResolver::class);
            $this->postTagObjectTypeResolver = $postTagObjectTypeResolver;
        }
        return $this->postTagObjectTypeResolver;
    }
    final protected function getPostTagDeleteMetaMutationPayloadObjectTypeResolver(): PostTagDeleteMetaMutationPayloadObjectTypeResolver
    {
        if ($this->postTagDeleteMetaMutationPayloadObjectTypeResolver === null) {
            /** @var PostTagDeleteMetaMutationPayloadObjectTypeResolver */
            $postTagDeleteMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PostTagDeleteMetaMutationPayloadObjectTypeResolver::class);
            $this->postTagDeleteMetaMutationPayloadObjectTypeResolver = $postTagDeleteMetaMutationPayloadObjectTypeResolver;
        }
        return $this->postTagDeleteMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getPostTagAddMetaMutationPayloadObjectTypeResolver(): PostTagAddMetaMutationPayloadObjectTypeResolver
    {
        if ($this->postTagCreateMutationPayloadObjectTypeResolver === null) {
            /** @var PostTagAddMetaMutationPayloadObjectTypeResolver */
            $postTagCreateMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PostTagAddMetaMutationPayloadObjectTypeResolver::class);
            $this->postTagCreateMutationPayloadObjectTypeResolver = $postTagCreateMutationPayloadObjectTypeResolver;
        }
        return $this->postTagCreateMutationPayloadObjectTypeResolver;
    }
    final protected function getPostTagUpdateMetaMutationPayloadObjectTypeResolver(): PostTagUpdateMetaMutationPayloadObjectTypeResolver
    {
        if ($this->postTagUpdateMetaMutationPayloadObjectTypeResolver === null) {
            /** @var PostTagUpdateMetaMutationPayloadObjectTypeResolver */
            $postTagUpdateMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PostTagUpdateMetaMutationPayloadObjectTypeResolver::class);
            $this->postTagUpdateMetaMutationPayloadObjectTypeResolver = $postTagUpdateMetaMutationPayloadObjectTypeResolver;
        }
        return $this->postTagUpdateMetaMutationPayloadObjectTypeResolver;
    }
    final protected function getPostTagSetMetaMutationPayloadObjectTypeResolver(): PostTagSetMetaMutationPayloadObjectTypeResolver
    {
        if ($this->postTagSetMetaMutationPayloadObjectTypeResolver === null) {
            /** @var PostTagSetMetaMutationPayloadObjectTypeResolver */
            $postTagSetMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PostTagSetMetaMutationPayloadObjectTypeResolver::class);
            $this->postTagSetMetaMutationPayloadObjectTypeResolver = $postTagSetMetaMutationPayloadObjectTypeResolver;
        }
        return $this->postTagSetMetaMutationPayloadObjectTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostTagObjectTypeResolver::class,
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
                    => $this->getPostTagObjectTypeResolver(),
                default
                    => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            'addMeta' => $this->getPostTagAddMetaMutationPayloadObjectTypeResolver(),
            'deleteMeta' => $this->getPostTagDeleteMetaMutationPayloadObjectTypeResolver(),
            'setMeta' => $this->getPostTagSetMetaMutationPayloadObjectTypeResolver(),
            'updateMeta' => $this->getPostTagUpdateMetaMutationPayloadObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
