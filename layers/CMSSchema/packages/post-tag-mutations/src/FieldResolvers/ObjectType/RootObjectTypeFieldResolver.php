<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostTagMutations\FieldResolvers\ObjectType\AbstractRootObjectTypeFieldResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\InputObjectType\AbstractSetTagsOnCustomPostInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;
use PoPCMSSchema\PostTagMutations\MutationResolvers\PayloadableSetTagsOnPostBulkOperationMutationResolver;
use PoPCMSSchema\PostTagMutations\MutationResolvers\PayloadableSetTagsOnPostMutationResolver;
use PoPCMSSchema\PostTagMutations\MutationResolvers\SetTagsOnPostBulkOperationMutationResolver;
use PoPCMSSchema\PostTagMutations\MutationResolvers\SetTagsOnPostMutationResolver;
use PoPCMSSchema\PostTagMutations\TypeResolvers\InputObjectType\RootSetTagsOnPostInputObjectTypeResolver;
use PoPCMSSchema\PostTagMutations\TypeResolvers\ObjectType\RootSetTagsOnPostMutationPayloadObjectTypeResolver;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;

class RootObjectTypeFieldResolver extends AbstractRootObjectTypeFieldResolver
{
    private ?PostObjectTypeResolver $postObjectTypeResolver = null;
    private ?SetTagsOnPostMutationResolver $setTagsOnPostMutationResolver = null;
    private ?SetTagsOnPostBulkOperationMutationResolver $setTagsOnPostBulkOperationMutationResolver = null;
    private ?RootSetTagsOnPostInputObjectTypeResolver $rootSetTagsOnPostInputObjectTypeResolver = null;
    private ?PayloadableSetTagsOnPostMutationResolver $payloadableSetTagsOnPostMutationResolver = null;
    private ?PayloadableSetTagsOnPostBulkOperationMutationResolver $payloadableSetTagsOnPostBulkOperationMutationResolver = null;
    private ?RootSetTagsOnPostMutationPayloadObjectTypeResolver $rootSetTagsOnPostMutationPayloadObjectTypeResolver = null;

    final public function setPostObjectTypeResolver(PostObjectTypeResolver $postObjectTypeResolver): void
    {
        $this->postObjectTypeResolver = $postObjectTypeResolver;
    }
    final protected function getPostObjectTypeResolver(): PostObjectTypeResolver
    {
        if ($this->postObjectTypeResolver === null) {
            /** @var PostObjectTypeResolver */
            $postObjectTypeResolver = $this->instanceManager->getInstance(PostObjectTypeResolver::class);
            $this->postObjectTypeResolver = $postObjectTypeResolver;
        }
        return $this->postObjectTypeResolver;
    }
    final public function setSetTagsOnPostMutationResolver(SetTagsOnPostMutationResolver $setTagsOnPostMutationResolver): void
    {
        $this->setTagsOnPostMutationResolver = $setTagsOnPostMutationResolver;
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
    final public function setSetTagsOnPostBulkOperationMutationResolver(SetTagsOnPostBulkOperationMutationResolver $setTagsOnPostBulkOperationMutationResolver): void
    {
        $this->setTagsOnPostBulkOperationMutationResolver = $setTagsOnPostBulkOperationMutationResolver;
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
    final public function setRootSetTagsOnPostInputObjectTypeResolver(RootSetTagsOnPostInputObjectTypeResolver $rootSetTagsOnPostInputObjectTypeResolver): void
    {
        $this->rootSetTagsOnPostInputObjectTypeResolver = $rootSetTagsOnPostInputObjectTypeResolver;
    }
    final protected function getRootSetTagsOnPostInputObjectTypeResolver(): AbstractSetTagsOnCustomPostInputObjectTypeResolver
    {
        if ($this->rootSetTagsOnPostInputObjectTypeResolver === null) {
            /** @var RootSetTagsOnPostInputObjectTypeResolver */
            $rootSetTagsOnPostInputObjectTypeResolver = $this->instanceManager->getInstance(RootSetTagsOnPostInputObjectTypeResolver::class);
            $this->rootSetTagsOnPostInputObjectTypeResolver = $rootSetTagsOnPostInputObjectTypeResolver;
        }
        return $this->rootSetTagsOnPostInputObjectTypeResolver;
    }
    final public function setPayloadableSetTagsOnPostBulkOperationMutationResolver(PayloadableSetTagsOnPostBulkOperationMutationResolver $payloadableSetTagsOnPostBulkOperationMutationResolver): void
    {
        $this->payloadableSetTagsOnPostBulkOperationMutationResolver = $payloadableSetTagsOnPostBulkOperationMutationResolver;
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
    final public function setPayloadableSetTagsOnPostMutationResolver(PayloadableSetTagsOnPostMutationResolver $payloadableSetTagsOnPostMutationResolver): void
    {
        $this->payloadableSetTagsOnPostMutationResolver = $payloadableSetTagsOnPostMutationResolver;
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
    final public function setRootSetTagsOnPostMutationPayloadObjectTypeResolver(RootSetTagsOnPostMutationPayloadObjectTypeResolver $rootSetTagsOnPostMutationPayloadObjectTypeResolver): void
    {
        $this->rootSetTagsOnPostMutationPayloadObjectTypeResolver = $rootSetTagsOnPostMutationPayloadObjectTypeResolver;
    }
    final protected function getRootSetTagsOnPostMutationPayloadObjectTypeResolver(): RootSetTagsOnPostMutationPayloadObjectTypeResolver
    {
        if ($this->rootSetTagsOnPostMutationPayloadObjectTypeResolver === null) {
            /** @var RootSetTagsOnPostMutationPayloadObjectTypeResolver */
            $rootSetTagsOnPostMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootSetTagsOnPostMutationPayloadObjectTypeResolver::class);
            $this->rootSetTagsOnPostMutationPayloadObjectTypeResolver = $rootSetTagsOnPostMutationPayloadObjectTypeResolver;
        }
        return $this->rootSetTagsOnPostMutationPayloadObjectTypeResolver;
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

    public function getPayloadableSetTagsMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableSetTagsOnPostMutationResolver();
    }

    public function getPayloadableSetTagsBulkOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableSetTagsOnPostBulkOperationMutationResolver();
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
        return $this->getRootSetTagsOnPostInputObjectTypeResolver();
    }

    protected function getSetTagsFieldName(): string
    {
        return 'setTagsOnPost';
    }

    protected function getBulkOperationSetTagsFieldName(): string
    {
        return 'setTagsOnPosts';
    }
}
