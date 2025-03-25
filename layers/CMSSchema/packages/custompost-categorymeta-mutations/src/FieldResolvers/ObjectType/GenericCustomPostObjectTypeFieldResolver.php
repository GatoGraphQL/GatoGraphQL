<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostCategoryMetaMutations\FieldResolvers\ObjectType\AbstractCustomPostObjectTypeFieldResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType\AbstractSetMetaOnCategoryInputObjectTypeResolver;
use PoPCMSSchema\Categories\TypeResolvers\ObjectType\GenericCategoryObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\PayloadableSetMetaOnCategoryBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\PayloadableSetMetaOnCategoryMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\SetMetaOnCategoryBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\SetMetaOnCategoryMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType\GenericCategorySetMetaInputObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\ObjectType\GenericCategorySetMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\Categories\TypeResolvers\ObjectType\GenericCategoryObjectTypeResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;

class GenericCustomPostObjectTypeFieldResolver extends AbstractCustomPostObjectTypeFieldResolver
{
    private ?GenericCategoryObjectTypeResolver $genericCategoryObjectTypeResolver = null;
    private ?SetMetaOnCategoryMutationResolver $setMetaOnCategoryMutationResolver = null;
    private ?SetMetaOnCategoryBulkOperationMutationResolver $setMetaOnCategoryBulkOperationMutationResolver = null;
    private ?GenericCategoryObjectTypeResolver $genericCategoryObjectTypeResolver = null;
    private ?GenericCategorySetMetaInputObjectTypeResolver $genericCategorySetMetaInputObjectTypeResolver = null;
    private ?PayloadableSetMetaOnCategoryMutationResolver $payloadableSetMetaOnCategoryMutationResolver = null;
    private ?PayloadableSetMetaOnCategoryBulkOperationMutationResolver $payloadableSetMetaOnCategoryBulkOperationMutationResolver = null;
    private ?GenericCategorySetMetaMutationPayloadObjectTypeResolver $genericCategorySetMetaMutationPayloadObjectTypeResolver = null;

    final protected function getGenericCategoryObjectTypeResolver(): GenericCategoryObjectTypeResolver
    {
        if ($this->genericCategoryObjectTypeResolver === null) {
            /** @var GenericCategoryObjectTypeResolver */
            $genericCategoryObjectTypeResolver = $this->instanceManager->getInstance(GenericCategoryObjectTypeResolver::class);
            $this->genericCategoryObjectTypeResolver = $genericCategoryObjectTypeResolver;
        }
        return $this->genericCategoryObjectTypeResolver;
    }
    final protected function getSetMetaOnCategoryMutationResolver(): SetMetaOnCategoryMutationResolver
    {
        if ($this->setMetaOnCategoryMutationResolver === null) {
            /** @var SetMetaOnCategoryMutationResolver */
            $setMetaOnCategoryMutationResolver = $this->instanceManager->getInstance(SetMetaOnCategoryMutationResolver::class);
            $this->setMetaOnCategoryMutationResolver = $setMetaOnCategoryMutationResolver;
        }
        return $this->setMetaOnCategoryMutationResolver;
    }
    final protected function getSetMetaOnCategoryBulkOperationMutationResolver(): SetMetaOnCategoryBulkOperationMutationResolver
    {
        if ($this->setMetaOnCategoryBulkOperationMutationResolver === null) {
            /** @var SetMetaOnCategoryBulkOperationMutationResolver */
            $setMetaOnCategoryBulkOperationMutationResolver = $this->instanceManager->getInstance(SetMetaOnCategoryBulkOperationMutationResolver::class);
            $this->setMetaOnCategoryBulkOperationMutationResolver = $setMetaOnCategoryBulkOperationMutationResolver;
        }
        return $this->setMetaOnCategoryBulkOperationMutationResolver;
    }
    final protected function getGenericCategoryObjectTypeResolver(): GenericCategoryObjectTypeResolver
    {
        if ($this->genericCategoryObjectTypeResolver === null) {
            /** @var GenericCategoryObjectTypeResolver */
            $genericCategoryObjectTypeResolver = $this->instanceManager->getInstance(GenericCategoryObjectTypeResolver::class);
            $this->genericCategoryObjectTypeResolver = $genericCategoryObjectTypeResolver;
        }
        return $this->genericCategoryObjectTypeResolver;
    }
    final protected function getGenericCategorySetMetaInputObjectTypeResolver(): AbstractSetMetaOnCategoryInputObjectTypeResolver
    {
        if ($this->genericCategorySetMetaInputObjectTypeResolver === null) {
            /** @var GenericCategorySetMetaInputObjectTypeResolver */
            $genericCategorySetMetaInputObjectTypeResolver = $this->instanceManager->getInstance(GenericCategorySetMetaInputObjectTypeResolver::class);
            $this->genericCategorySetMetaInputObjectTypeResolver = $genericCategorySetMetaInputObjectTypeResolver;
        }
        return $this->genericCategorySetMetaInputObjectTypeResolver;
    }
    final protected function getPayloadableSetMetaOnCategoryMutationResolver(): PayloadableSetMetaOnCategoryMutationResolver
    {
        if ($this->payloadableSetMetaOnCategoryMutationResolver === null) {
            /** @var PayloadableSetMetaOnCategoryMutationResolver */
            $payloadableSetMetaOnCategoryMutationResolver = $this->instanceManager->getInstance(PayloadableSetMetaOnCategoryMutationResolver::class);
            $this->payloadableSetMetaOnCategoryMutationResolver = $payloadableSetMetaOnCategoryMutationResolver;
        }
        return $this->payloadableSetMetaOnCategoryMutationResolver;
    }
    final protected function getPayloadableSetMetaOnCategoryBulkOperationMutationResolver(): PayloadableSetMetaOnCategoryBulkOperationMutationResolver
    {
        if ($this->payloadableSetMetaOnCategoryBulkOperationMutationResolver === null) {
            /** @var PayloadableSetMetaOnCategoryBulkOperationMutationResolver */
            $payloadableSetMetaOnCategoryBulkOperationMutationResolver = $this->instanceManager->getInstance(PayloadableSetMetaOnCategoryBulkOperationMutationResolver::class);
            $this->payloadableSetMetaOnCategoryBulkOperationMutationResolver = $payloadableSetMetaOnCategoryBulkOperationMutationResolver;
        }
        return $this->payloadableSetMetaOnCategoryBulkOperationMutationResolver;
    }
    final protected function getGenericCategorySetMetaMutationPayloadObjectTypeResolver(): GenericCategorySetMetaMutationPayloadObjectTypeResolver
    {
        if ($this->genericCategorySetMetaMutationPayloadObjectTypeResolver === null) {
            /** @var GenericCategorySetMetaMutationPayloadObjectTypeResolver */
            $genericCategorySetMetaMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(GenericCategorySetMetaMutationPayloadObjectTypeResolver::class);
            $this->genericCategorySetMetaMutationPayloadObjectTypeResolver = $genericCategorySetMetaMutationPayloadObjectTypeResolver;
        }
        return $this->genericCategorySetMetaMutationPayloadObjectTypeResolver;
    }

    public function getCategoryObjectTypeResolver(): CategoryObjectTypeResolverInterface
    {
        return $this->getGenericCategoryObjectTypeResolver();
    }

    public function getSetCategoriesMutationResolver(): MutationResolverInterface
    {
        return $this->getSetMetaOnCategoryMutationResolver();
    }

    public function getSetCategoriesBulkOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getSetMetaOnCategoryBulkOperationMutationResolver();
    }

    public function getCategoryTypeResolver(): CategoryObjectTypeResolverInterface
    {
        return $this->getGenericCategoryObjectTypeResolver();
    }

    public function getCategorySetMetaInputObjectTypeResolver(): AbstractSetMetaOnCategoryInputObjectTypeResolver
    {
        return $this->getGenericCategorySetMetaInputObjectTypeResolver();
    }

    protected function getCategorySetMetaMutationPayloadObjectTypeResolver(): ConcreteTypeResolverInterface
    {
        return $this->getGenericCategorySetMetaMutationPayloadObjectTypeResolver();
    }

    public function getPayloadableSetCategoriesMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableSetMetaOnCategoryMutationResolver();
    }

    public function getPayloadableSetCategoriesBulkOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableSetMetaOnCategoryBulkOperationMutationResolver();
    }

    protected function getEntityName(): string
    {
        return $this->__('custom post', 'category-mutations');
    }
}
