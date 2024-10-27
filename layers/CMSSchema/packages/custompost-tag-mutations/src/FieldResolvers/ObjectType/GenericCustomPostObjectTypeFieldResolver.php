<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\Tags\TypeResolvers\ObjectType\TagObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostTagMutations\FieldResolvers\ObjectType\AbstractCustomPostObjectTypeFieldResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\InputObjectType\AbstractSetTagsOnCustomPostInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;
use PoPCMSSchema\Tags\TypeResolvers\ObjectType\GenericTagObjectTypeResolver;
use PoPCMSSchema\CustomPostTagMutations\MutationResolvers\PayloadableSetTagsOnCustomPostBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostTagMutations\MutationResolvers\PayloadableSetTagsOnCustomPostMutationResolver;
use PoPCMSSchema\CustomPostTagMutations\MutationResolvers\SetTagsOnCustomPostBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostTagMutations\MutationResolvers\SetTagsOnCustomPostMutationResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\InputObjectType\GenericCustomPostSetTagsInputObjectTypeResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\ObjectType\GenericCustomPostSetTagsMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\GenericCustomPostObjectTypeResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;

class GenericCustomPostObjectTypeFieldResolver extends AbstractCustomPostObjectTypeFieldResolver
{
    private ?GenericCustomPostObjectTypeResolver $genericCustomPostObjectTypeResolver = null;
    private ?SetTagsOnCustomPostMutationResolver $setTagsOnCustomPostMutationResolver = null;
    private ?SetTagsOnCustomPostBulkOperationMutationResolver $setTagsOnCustomPostBulkOperationMutationResolver = null;
    private ?GenericTagObjectTypeResolver $genericTagObjectTypeResolver = null;
    private ?GenericCustomPostSetTagsInputObjectTypeResolver $genericCustomPostSetTagsInputObjectTypeResolver = null;
    private ?PayloadableSetTagsOnCustomPostMutationResolver $payloadableSetTagsOnCustomPostMutationResolver = null;
    private ?PayloadableSetTagsOnCustomPostBulkOperationMutationResolver $payloadableSetTagsOnCustomPostBulkOperationMutationResolver = null;
    private ?GenericCustomPostSetTagsMutationPayloadObjectTypeResolver $genericCustomPostSetTagsMutationPayloadObjectTypeResolver = null;

    final protected function getGenericCustomPostObjectTypeResolver(): GenericCustomPostObjectTypeResolver
    {
        if ($this->genericCustomPostObjectTypeResolver === null) {
            /** @var GenericCustomPostObjectTypeResolver */
            $genericCustomPostObjectTypeResolver = $this->instanceManager->getInstance(GenericCustomPostObjectTypeResolver::class);
            $this->genericCustomPostObjectTypeResolver = $genericCustomPostObjectTypeResolver;
        }
        return $this->genericCustomPostObjectTypeResolver;
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
    final protected function getSetTagsOnCustomPostBulkOperationMutationResolver(): SetTagsOnCustomPostBulkOperationMutationResolver
    {
        if ($this->setTagsOnCustomPostBulkOperationMutationResolver === null) {
            /** @var SetTagsOnCustomPostBulkOperationMutationResolver */
            $setTagsOnCustomPostBulkOperationMutationResolver = $this->instanceManager->getInstance(SetTagsOnCustomPostBulkOperationMutationResolver::class);
            $this->setTagsOnCustomPostBulkOperationMutationResolver = $setTagsOnCustomPostBulkOperationMutationResolver;
        }
        return $this->setTagsOnCustomPostBulkOperationMutationResolver;
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
    final protected function getGenericCustomPostSetTagsInputObjectTypeResolver(): AbstractSetTagsOnCustomPostInputObjectTypeResolver
    {
        if ($this->genericCustomPostSetTagsInputObjectTypeResolver === null) {
            /** @var GenericCustomPostSetTagsInputObjectTypeResolver */
            $genericCustomPostSetTagsInputObjectTypeResolver = $this->instanceManager->getInstance(GenericCustomPostSetTagsInputObjectTypeResolver::class);
            $this->genericCustomPostSetTagsInputObjectTypeResolver = $genericCustomPostSetTagsInputObjectTypeResolver;
        }
        return $this->genericCustomPostSetTagsInputObjectTypeResolver;
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
    final protected function getPayloadableSetTagsOnCustomPostBulkOperationMutationResolver(): PayloadableSetTagsOnCustomPostBulkOperationMutationResolver
    {
        if ($this->payloadableSetTagsOnCustomPostBulkOperationMutationResolver === null) {
            /** @var PayloadableSetTagsOnCustomPostBulkOperationMutationResolver */
            $payloadableSetTagsOnCustomPostBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableSetTagsOnCustomPostBulkOperationMutationResolver::class);
            $this->payloadableSetTagsOnCustomPostBulkOperationMutationResolver = $payloadableSetTagsOnCustomPostBulkOperationMutationResolver;
        }
        return $this->payloadableSetTagsOnCustomPostBulkOperationMutationResolver;
    }
    final protected function getGenericCustomPostSetTagsMutationPayloadObjectTypeResolver(): GenericCustomPostSetTagsMutationPayloadObjectTypeResolver
    {
        if ($this->genericCustomPostSetTagsMutationPayloadObjectTypeResolver === null) {
            /** @var GenericCustomPostSetTagsMutationPayloadObjectTypeResolver */
            $genericCustomPostSetTagsMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(GenericCustomPostSetTagsMutationPayloadObjectTypeResolver::class);
            $this->genericCustomPostSetTagsMutationPayloadObjectTypeResolver = $genericCustomPostSetTagsMutationPayloadObjectTypeResolver;
        }
        return $this->genericCustomPostSetTagsMutationPayloadObjectTypeResolver;
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
        return $this->getGenericCustomPostSetTagsInputObjectTypeResolver();
    }

    protected function getCustomPostSetTagsMutationPayloadObjectTypeResolver(): ConcreteTypeResolverInterface
    {
        return $this->getGenericCustomPostSetTagsMutationPayloadObjectTypeResolver();
    }

    public function getPayloadableSetTagsMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableSetTagsOnCustomPostMutationResolver();
    }

    public function getPayloadableSetTagsBulkOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableSetTagsOnCustomPostBulkOperationMutationResolver();
    }

    protected function getEntityName(): string
    {
        return $this->__('custom post', 'tag-mutations');
    }
}
