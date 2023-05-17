<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostTagMutations\FieldResolvers\ObjectType\AbstractRootObjectTypeFieldResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\InputObjectType\AbstractSetTagsOnCustomPostInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;
use PoPCMSSchema\PostTagMutations\MutationResolvers\PayloadableSetTagsOnPostMutationResolver;
use PoPCMSSchema\PostTagMutations\MutationResolvers\SetTagsOnPostMutationResolver;
use PoPCMSSchema\PostTagMutations\TypeResolvers\InputObjectType\RootSetTagsOnCustomPostInputObjectTypeResolver;
use PoPCMSSchema\PostTagMutations\TypeResolvers\ObjectType\RootSetTagsOnPostMutationPayloadObjectTypeResolver;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;

class RootObjectTypeFieldResolver extends AbstractRootObjectTypeFieldResolver
{
    private ?PostObjectTypeResolver $postObjectTypeResolver = null;
    private ?SetTagsOnPostMutationResolver $setTagsOnPostMutationResolver = null;
    private ?RootSetTagsOnCustomPostInputObjectTypeResolver $rootSetTagsOnCustomPostInputObjectTypeResolver = null;
    private ?PayloadableSetTagsOnPostMutationResolver $payloadableSetTagsOnPostMutationResolver = null;
    private ?RootSetTagsOnPostMutationPayloadObjectTypeResolver $rootSetTagsOnPostMutationPayloadObjectTypeResolver = null;

    final public function setPostObjectTypeResolver(PostObjectTypeResolver $postObjectTypeResolver): void
    {
        $this->postObjectTypeResolver = $postObjectTypeResolver;
    }
    final protected function getPostObjectTypeResolver(): PostObjectTypeResolver
    {
        /** @var PostObjectTypeResolver */
        return $this->postObjectTypeResolver ??= $this->instanceManager->getInstance(PostObjectTypeResolver::class);
    }
    final public function setSetTagsOnPostMutationResolver(SetTagsOnPostMutationResolver $setTagsOnPostMutationResolver): void
    {
        $this->setTagsOnPostMutationResolver = $setTagsOnPostMutationResolver;
    }
    final protected function getSetTagsOnPostMutationResolver(): SetTagsOnPostMutationResolver
    {
        /** @var SetTagsOnPostMutationResolver */
        return $this->setTagsOnPostMutationResolver ??= $this->instanceManager->getInstance(SetTagsOnPostMutationResolver::class);
    }
    final public function setRootSetTagsOnCustomPostInputObjectTypeResolver(RootSetTagsOnCustomPostInputObjectTypeResolver $rootSetTagsOnCustomPostInputObjectTypeResolver): void
    {
        $this->rootSetTagsOnCustomPostInputObjectTypeResolver = $rootSetTagsOnCustomPostInputObjectTypeResolver;
    }
    final protected function getRootSetTagsOnCustomPostInputObjectTypeResolver(): AbstractSetTagsOnCustomPostInputObjectTypeResolver
    {
        /** @var RootSetTagsOnCustomPostInputObjectTypeResolver */
        return $this->rootSetTagsOnCustomPostInputObjectTypeResolver ??= $this->instanceManager->getInstance(RootSetTagsOnCustomPostInputObjectTypeResolver::class);
    }
    final public function setPayloadableSetTagsOnPostMutationResolver(PayloadableSetTagsOnPostMutationResolver $payloadableSetTagsOnPostMutationResolver): void
    {
        $this->payloadableSetTagsOnPostMutationResolver = $payloadableSetTagsOnPostMutationResolver;
    }
    final protected function getPayloadableSetTagsOnPostMutationResolver(): PayloadableSetTagsOnPostMutationResolver
    {
        /** @var PayloadableSetTagsOnPostMutationResolver */
        return $this->payloadableSetTagsOnPostMutationResolver ??= $this->instanceManager->getInstance(PayloadableSetTagsOnPostMutationResolver::class);
    }
    final public function setRootSetTagsOnPostMutationPayloadObjectTypeResolver(RootSetTagsOnPostMutationPayloadObjectTypeResolver $rootSetTagsOnPostMutationPayloadObjectTypeResolver): void
    {
        $this->rootSetTagsOnPostMutationPayloadObjectTypeResolver = $rootSetTagsOnPostMutationPayloadObjectTypeResolver;
    }
    final protected function getRootSetTagsOnPostMutationPayloadObjectTypeResolver(): RootSetTagsOnPostMutationPayloadObjectTypeResolver
    {
        /** @var RootSetTagsOnPostMutationPayloadObjectTypeResolver */
        return $this->rootSetTagsOnPostMutationPayloadObjectTypeResolver ??= $this->instanceManager->getInstance(RootSetTagsOnPostMutationPayloadObjectTypeResolver::class);
    }

    public function getCustomPostObjectTypeResolver(): CustomPostObjectTypeResolverInterface
    {
        return $this->getPostObjectTypeResolver();
    }

    public function getSetTagsMutationResolver(): MutationResolverInterface
    {
        return $this->getSetTagsOnPostMutationResolver();
    }

    public function getPayloadableSetTagsMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableSetTagsOnPostMutationResolver();
    }

    protected function getRootSetTagsMutationPayloadObjectTypeResolver(): ConcreteTypeResolverInterface
    {
        return $this->getRootSetTagsOnPostMutationPayloadObjectTypeResolver();
    }

    protected function getEntityName(): string
    {
        return $this->__('post', 'post-tag-mutations');
    }

    public function getCustomPostSetTagsInputObjectTypeResolver(): AbstractSetTagsOnCustomPostInputObjectTypeResolver
    {
        return $this->getRootSetTagsOnCustomPostInputObjectTypeResolver();
    }

    protected function getSetTagsFieldName(): string
    {
        return 'setTagsOnPost';
    }
}
