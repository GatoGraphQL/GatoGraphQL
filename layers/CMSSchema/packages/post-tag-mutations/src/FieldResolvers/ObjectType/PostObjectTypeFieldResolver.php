<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostTagMutations\FieldResolvers\ObjectType\AbstractCustomPostObjectTypeFieldResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\InputObjectType\AbstractSetTagsOnCustomPostInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;
use PoPCMSSchema\PostTagMutations\MutationResolvers\PayloadableSetTagsOnPostBulkOperationMutationResolver;
use PoPCMSSchema\PostTagMutations\MutationResolvers\PayloadableSetTagsOnPostMutationResolver;
use PoPCMSSchema\PostTagMutations\MutationResolvers\SetTagsOnPostBulkOperationMutationResolver;
use PoPCMSSchema\PostTagMutations\MutationResolvers\SetTagsOnPostMutationResolver;
use PoPCMSSchema\PostTagMutations\TypeResolvers\InputObjectType\PostSetTagsInputObjectTypeResolver;
use PoPCMSSchema\PostTagMutations\TypeResolvers\ObjectType\PostSetTagsMutationPayloadObjectTypeResolver;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;

class PostObjectTypeFieldResolver extends AbstractCustomPostObjectTypeFieldResolver
{
    private ?PostObjectTypeResolver $postObjectTypeResolver = null;
    private ?SetTagsOnPostMutationResolver $setTagsOnPostMutationResolver = null;
    private ?SetTagsOnPostBulkOperationMutationResolver $setTagsOnPostBulkOperationMutationResolver = null;
    private ?PostSetTagsInputObjectTypeResolver $postSetTagsInputObjectTypeResolver = null;
    private ?PayloadableSetTagsOnPostMutationResolver $payloadableSetTagsOnPostMutationResolver = null;
    private ?PayloadableSetTagsOnPostBulkOperationMutationResolver $payloadableSetTagsOnPostBulkOperationMutationResolver = null;
    private ?PostSetTagsMutationPayloadObjectTypeResolver $postSetTagsMutationPayloadObjectTypeResolver = null;

    final protected function getPostObjectTypeResolver(): PostObjectTypeResolver
    {
        if ($this->postObjectTypeResolver === null) {
            /** @var PostObjectTypeResolver */
            $postObjectTypeResolver = $this->instanceManager->getInstance(PostObjectTypeResolver::class);
            $this->postObjectTypeResolver = $postObjectTypeResolver;
        }
        return $this->postObjectTypeResolver;
    }
    final protected function getSetTagsOnPostBulkOperationMutationResolver(): SetTagsOnPostBulkOperationMutationResolver
    {
        if ($this->setTagsOnPostBulkOperationMutationResolver === null) {
            /** @var SetTagsOnPostBulkOperationMutationResolver */
            $setTagsOnPostBulkOperationMutationResolver = $this->instanceManager->getInstance(SetTagsOnPostBulkOperationMutationResolver::class);
            $this->setTagsOnPostBulkOperationMutationResolver = $setTagsOnPostBulkOperationMutationResolver;
        }
        return $this->setTagsOnPostBulkOperationMutationResolver;
    }
    final protected function getSetTagsOnPostMutationResolver(): SetTagsOnPostMutationResolver
    {
        if ($this->setTagsOnPostMutationResolver === null) {
            /** @var SetTagsOnPostMutationResolver */
            $setTagsOnPostMutationResolver = $this->instanceManager->getInstance(SetTagsOnPostMutationResolver::class);
            $this->setTagsOnPostMutationResolver = $setTagsOnPostMutationResolver;
        }
        return $this->setTagsOnPostMutationResolver;
    }
    final protected function getPostSetTagsInputObjectTypeResolver(): AbstractSetTagsOnCustomPostInputObjectTypeResolver
    {
        if ($this->postSetTagsInputObjectTypeResolver === null) {
            /** @var PostSetTagsInputObjectTypeResolver */
            $postSetTagsInputObjectTypeResolver = $this->instanceManager->getInstance(PostSetTagsInputObjectTypeResolver::class);
            $this->postSetTagsInputObjectTypeResolver = $postSetTagsInputObjectTypeResolver;
        }
        return $this->postSetTagsInputObjectTypeResolver;
    }
    final protected function getPayloadableSetTagsOnPostMutationResolver(): PayloadableSetTagsOnPostMutationResolver
    {
        if ($this->payloadableSetTagsOnPostMutationResolver === null) {
            /** @var PayloadableSetTagsOnPostMutationResolver */
            $payloadableSetTagsOnPostMutationResolver = $this->instanceManager->getInstance(PayloadableSetTagsOnPostMutationResolver::class);
            $this->payloadableSetTagsOnPostMutationResolver = $payloadableSetTagsOnPostMutationResolver;
        }
        return $this->payloadableSetTagsOnPostMutationResolver;
    }
    final protected function getPayloadableSetTagsOnPostBulkOperationMutationResolver(): PayloadableSetTagsOnPostBulkOperationMutationResolver
    {
        if ($this->payloadableSetTagsOnPostBulkOperationMutationResolver === null) {
            /** @var PayloadableSetTagsOnPostBulkOperationMutationResolver */
            $payloadableSetTagsOnPostBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableSetTagsOnPostBulkOperationMutationResolver::class);
            $this->payloadableSetTagsOnPostBulkOperationMutationResolver = $payloadableSetTagsOnPostBulkOperationMutationResolver;
        }
        return $this->payloadableSetTagsOnPostBulkOperationMutationResolver;
    }
    final protected function getPostSetTagsMutationPayloadObjectTypeResolver(): PostSetTagsMutationPayloadObjectTypeResolver
    {
        if ($this->postSetTagsMutationPayloadObjectTypeResolver === null) {
            /** @var PostSetTagsMutationPayloadObjectTypeResolver */
            $postSetTagsMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(PostSetTagsMutationPayloadObjectTypeResolver::class);
            $this->postSetTagsMutationPayloadObjectTypeResolver = $postSetTagsMutationPayloadObjectTypeResolver;
        }
        return $this->postSetTagsMutationPayloadObjectTypeResolver;
    }

    public function getCustomPostObjectTypeResolver(): CustomPostObjectTypeResolverInterface
    {
        return $this->getPostObjectTypeResolver();
    }

    public function getSetTagsMutationResolver(): MutationResolverInterface
    {
        return $this->getSetTagsOnPostMutationResolver();
    }

    public function getSetTagsBulkOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getSetTagsOnPostBulkOperationMutationResolver();
    }

    public function getCustomPostSetTagsInputObjectTypeResolver(): AbstractSetTagsOnCustomPostInputObjectTypeResolver
    {
        return $this->getPostSetTagsInputObjectTypeResolver();
    }

    protected function getCustomPostSetTagsMutationPayloadObjectTypeResolver(): ConcreteTypeResolverInterface
    {
        return $this->getPostSetTagsMutationPayloadObjectTypeResolver();
    }

    public function getPayloadableSetTagsMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableSetTagsOnPostMutationResolver();
    }

    public function getPayloadableSetTagsBulkOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableSetTagsOnPostBulkOperationMutationResolver();
    }

    protected function getEntityName(): string
    {
        return $this->__('post', 'post-tag-mutations');
    }
}
