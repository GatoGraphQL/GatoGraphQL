<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\Tags\TypeResolvers\ObjectType\TagObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostTagMutations\FieldResolvers\ObjectType\AbstractRootObjectTypeFieldResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\InputObjectType\AbstractSetTagsOnCustomPostInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;
use PoPCMSSchema\Tags\TypeResolvers\ObjectType\GenericTagObjectTypeResolver;
use PoPCMSSchema\CustomPostTagMutations\MutationResolvers\PayloadableSetTagsOnCustomPostBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostTagMutations\MutationResolvers\PayloadableSetTagsOnCustomPostMutationResolver;
use PoPCMSSchema\CustomPostTagMutations\MutationResolvers\SetTagsOnCustomPostBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostTagMutations\MutationResolvers\SetTagsOnCustomPostMutationResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\InputObjectType\RootSetTagsOnCustomPostInputObjectTypeResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\ObjectType\RootSetTagsOnCustomPostMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\GenericCustomPostObjectTypeResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;

class RootObjectTypeFieldResolver extends AbstractRootObjectTypeFieldResolver
{
    private ?GenericCustomPostObjectTypeResolver $genericCustomPostObjectTypeResolver = null;
    private ?SetTagsOnCustomPostMutationResolver $setTagsOnCustomPostMutationResolver = null;
    private ?SetTagsOnCustomPostBulkOperationMutationResolver $setTagsOnCustomPostBulkOperationMutationResolver = null;
    private ?GenericTagObjectTypeResolver $genericTagObjectTypeResolver = null;
    private ?RootSetTagsOnCustomPostInputObjectTypeResolver $rootSetTagsOnCustomPostInputObjectTypeResolver = null;
    private ?PayloadableSetTagsOnCustomPostMutationResolver $payloadableSetTagsOnCustomPostMutationResolver = null;
    private ?PayloadableSetTagsOnCustomPostBulkOperationMutationResolver $payloadableSetTagsOnCustomPostBulkOperationMutationResolver = null;
    private ?RootSetTagsOnCustomPostMutationPayloadObjectTypeResolver $rootSetTagsOnCustomPostMutationPayloadObjectTypeResolver = null;

    final public function setGenericCustomPostObjectTypeResolver(GenericCustomPostObjectTypeResolver $genericCustomPostObjectTypeResolver): void
    {
        $this->genericCustomPostObjectTypeResolver = $genericCustomPostObjectTypeResolver;
    }
    final protected function getGenericCustomPostObjectTypeResolver(): GenericCustomPostObjectTypeResolver
    {
        if ($this->genericCustomPostObjectTypeResolver === null) {
            /** @var GenericCustomPostObjectTypeResolver */
            $genericCustomPostObjectTypeResolver = $this->instanceManager->getInstance(GenericCustomPostObjectTypeResolver::class);
            $this->genericCustomPostObjectTypeResolver = $genericCustomPostObjectTypeResolver;
        }
        return $this->genericCustomPostObjectTypeResolver;
    }
    final public function setSetTagsOnCustomPostMutationResolver(SetTagsOnCustomPostMutationResolver $setTagsOnCustomPostMutationResolver): void
    {
        $this->setTagsOnCustomPostMutationResolver = $setTagsOnCustomPostMutationResolver;
    }
    final protected function getSetTagsOnCustomPostMutationResolver(): SetTagsOnCustomPostMutationResolver
    {
        if ($this->setTagsOnCustomPostMutationResolver === null) {
            /** @var SetTagsOnCustomPostMutationResolver */
            $setTagsOnCustomPostMutationResolver = $this->instanceManager->getInstance(SetTagsOnCustomPostMutationResolver::class);
            $this->setTagsOnCustomPostMutationResolver = $setTagsOnCustomPostMutationResolver;
        }
        return $this->setTagsOnCustomPostMutationResolver;
    }
    final public function setSetTagsOnCustomPostBulkOperationMutationResolver(SetTagsOnCustomPostBulkOperationMutationResolver $setTagsOnCustomPostBulkOperationMutationResolver): void
    {
        $this->setTagsOnCustomPostBulkOperationMutationResolver = $setTagsOnCustomPostBulkOperationMutationResolver;
    }
    final protected function getSetTagsOnCustomPostBulkOperationMutationResolver(): SetTagsOnCustomPostBulkOperationMutationResolver
    {
        if ($this->setTagsOnCustomPostBulkOperationMutationResolver === null) {
            /** @var SetTagsOnCustomPostBulkOperationMutationResolver */
            $setTagsOnCustomPostBulkOperationMutationResolver = $this->instanceManager->getInstance(SetTagsOnCustomPostBulkOperationMutationResolver::class);
            $this->setTagsOnCustomPostBulkOperationMutationResolver = $setTagsOnCustomPostBulkOperationMutationResolver;
        }
        return $this->setTagsOnCustomPostBulkOperationMutationResolver;
    }
    final public function setGenericTagObjectTypeResolver(GenericTagObjectTypeResolver $genericTagObjectTypeResolver): void
    {
        $this->genericTagObjectTypeResolver = $genericTagObjectTypeResolver;
    }
    final protected function getGenericTagObjectTypeResolver(): GenericTagObjectTypeResolver
    {
        if ($this->genericTagObjectTypeResolver === null) {
            /** @var GenericTagObjectTypeResolver */
            $genericTagObjectTypeResolver = $this->instanceManager->getInstance(GenericTagObjectTypeResolver::class);
            $this->genericTagObjectTypeResolver = $genericTagObjectTypeResolver;
        }
        return $this->genericTagObjectTypeResolver;
    }
    final public function setRootSetTagsOnCustomPostInputObjectTypeResolver(RootSetTagsOnCustomPostInputObjectTypeResolver $rootSetTagsOnCustomPostInputObjectTypeResolver): void
    {
        $this->rootSetTagsOnCustomPostInputObjectTypeResolver = $rootSetTagsOnCustomPostInputObjectTypeResolver;
    }
    final protected function getRootSetTagsOnCustomPostInputObjectTypeResolver(): AbstractSetTagsOnCustomPostInputObjectTypeResolver
    {
        if ($this->rootSetTagsOnCustomPostInputObjectTypeResolver === null) {
            /** @var RootSetTagsOnCustomPostInputObjectTypeResolver */
            $rootSetTagsOnCustomPostInputObjectTypeResolver = $this->instanceManager->getInstance(RootSetTagsOnCustomPostInputObjectTypeResolver::class);
            $this->rootSetTagsOnCustomPostInputObjectTypeResolver = $rootSetTagsOnCustomPostInputObjectTypeResolver;
        }
        return $this->rootSetTagsOnCustomPostInputObjectTypeResolver;
    }
    final public function setPayloadableSetTagsOnCustomPostMutationResolver(PayloadableSetTagsOnCustomPostMutationResolver $payloadableSetTagsOnCustomPostMutationResolver): void
    {
        $this->payloadableSetTagsOnCustomPostMutationResolver = $payloadableSetTagsOnCustomPostMutationResolver;
    }
    final protected function getPayloadableSetTagsOnCustomPostMutationResolver(): PayloadableSetTagsOnCustomPostMutationResolver
    {
        if ($this->payloadableSetTagsOnCustomPostMutationResolver === null) {
            /** @var PayloadableSetTagsOnCustomPostMutationResolver */
            $payloadableSetTagsOnCustomPostMutationResolver = $this->instanceManager->getInstance(PayloadableSetTagsOnCustomPostMutationResolver::class);
            $this->payloadableSetTagsOnCustomPostMutationResolver = $payloadableSetTagsOnCustomPostMutationResolver;
        }
        return $this->payloadableSetTagsOnCustomPostMutationResolver;
    }
    final public function setPayloadableSetTagsOnCustomPostBulkOperationMutationResolver(PayloadableSetTagsOnCustomPostBulkOperationMutationResolver $payloadableSetTagsOnCustomPostBulkOperationMutationResolver): void
    {
        $this->payloadableSetTagsOnCustomPostBulkOperationMutationResolver = $payloadableSetTagsOnCustomPostBulkOperationMutationResolver;
    }
    final protected function getPayloadableSetTagsOnCustomPostBulkOperationMutationResolver(): PayloadableSetTagsOnCustomPostBulkOperationMutationResolver
    {
        if ($this->payloadableSetTagsOnCustomPostBulkOperationMutationResolver === null) {
            /** @var PayloadableSetTagsOnCustomPostBulkOperationMutationResolver */
            $payloadableSetTagsOnCustomPostBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableSetTagsOnCustomPostBulkOperationMutationResolver::class);
            $this->payloadableSetTagsOnCustomPostBulkOperationMutationResolver = $payloadableSetTagsOnCustomPostBulkOperationMutationResolver;
        }
        return $this->payloadableSetTagsOnCustomPostBulkOperationMutationResolver;
    }
    final public function setRootSetTagsOnCustomPostMutationPayloadObjectTypeResolver(RootSetTagsOnCustomPostMutationPayloadObjectTypeResolver $rootSetTagsOnCustomPostMutationPayloadObjectTypeResolver): void
    {
        $this->rootSetTagsOnCustomPostMutationPayloadObjectTypeResolver = $rootSetTagsOnCustomPostMutationPayloadObjectTypeResolver;
    }
    final protected function getRootSetTagsOnCustomPostMutationPayloadObjectTypeResolver(): RootSetTagsOnCustomPostMutationPayloadObjectTypeResolver
    {
        if ($this->rootSetTagsOnCustomPostMutationPayloadObjectTypeResolver === null) {
            /** @var RootSetTagsOnCustomPostMutationPayloadObjectTypeResolver */
            $rootSetTagsOnCustomPostMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootSetTagsOnCustomPostMutationPayloadObjectTypeResolver::class);
            $this->rootSetTagsOnCustomPostMutationPayloadObjectTypeResolver = $rootSetTagsOnCustomPostMutationPayloadObjectTypeResolver;
        }
        return $this->rootSetTagsOnCustomPostMutationPayloadObjectTypeResolver;
    }

    public function getCustomPostObjectTypeResolver(): CustomPostObjectTypeResolverInterface
    {
        return $this->getGenericCustomPostObjectTypeResolver();
    }

    public function getSetTagsMutationResolver(): MutationResolverInterface
    {
        return $this->getSetTagsOnCustomPostMutationResolver();
    }

    public function getSetTagsBulkOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getSetTagsOnCustomPostBulkOperationMutationResolver();
    }

    public function getTagTypeResolver(): TagObjectTypeResolverInterface
    {
        return $this->getGenericTagObjectTypeResolver();
    }

    public function getCustomPostSetTagsInputObjectTypeResolver(): AbstractSetTagsOnCustomPostInputObjectTypeResolver
    {
        return $this->getRootSetTagsOnCustomPostInputObjectTypeResolver();
    }

    public function getPayloadableSetTagsMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableSetTagsOnCustomPostMutationResolver();
    }

    public function getPayloadableSetTagsBulkOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableSetTagsOnCustomPostBulkOperationMutationResolver();
    }

    protected function getRootSetTagsMutationPayloadObjectTypeResolver(): ConcreteTypeResolverInterface
    {
        return $this->getRootSetTagsOnCustomPostMutationPayloadObjectTypeResolver();
    }

    protected function getEntityName(): string
    {
        return $this->__('custom post', 'post-tag-mutations');
    }

    protected function getSetTagsFieldName(): string
    {
        return 'setTagsOnCustomPost';
    }

    protected function getBulkOperationSetTagsFieldName(): string
    {
        return 'setTagsOnCustomPosts';
    }
}
