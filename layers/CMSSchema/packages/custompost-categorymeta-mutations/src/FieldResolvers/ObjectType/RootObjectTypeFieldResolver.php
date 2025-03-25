<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostCategoryMetaMutations\FieldResolvers\ObjectType\AbstractRootObjectTypeFieldResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType\AbstractSetMetaOnCategoryInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;
use PoPCMSSchema\Categories\TypeResolvers\ObjectType\GenericCategoryObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\PayloadableSetMetaOnCategoryBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\PayloadableSetMetaOnCategoryMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\SetMetaOnCategoryBulkOperationMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers\SetMetaOnCategoryMutationResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType\RootSetMetaOnCategoryInputObjectTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\ObjectType\RootSetMetaOnCategoryMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\GenericCustomPostObjectTypeResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;

class RootObjectTypeFieldResolver extends AbstractRootObjectTypeFieldResolver
{
    private ?GenericCustomPostObjectTypeResolver $genericCustomPostObjectTypeResolver = null;
    private ?SetMetaOnCategoryMutationResolver $setMetaOnCategoryMutationResolver = null;
    private ?SetMetaOnCategoryBulkOperationMutationResolver $setMetaOnCategoryBulkOperationMutationResolver = null;
    private ?GenericCategoryObjectTypeResolver $genericCategoryObjectTypeResolver = null;
    private ?RootSetMetaOnCategoryInputObjectTypeResolver $rootSetMetaOnCategoryInputObjectTypeResolver = null;
    private ?PayloadableSetMetaOnCategoryMutationResolver $payloadableSetMetaOnCategoryMutationResolver = null;
    private ?PayloadableSetMetaOnCategoryBulkOperationMutationResolver $payloadableSetMetaOnCategoryBulkOperationMutationResolver = null;
    private ?RootSetMetaOnCategoryMutationPayloadObjectTypeResolver $rootSetMetaOnCategoryMutationPayloadObjectTypeResolver = null;

    final protected function getGenericCustomPostObjectTypeResolver(): GenericCustomPostObjectTypeResolver
    {
        if ($this->genericCustomPostObjectTypeResolver === null) {
            /** @var GenericCustomPostObjectTypeResolver */
            $genericCustomPostObjectTypeResolver = $this->instanceManager->getInstance(GenericCustomPostObjectTypeResolver::class);
            $this->genericCustomPostObjectTypeResolver = $genericCustomPostObjectTypeResolver;
        }
        return $this->genericCustomPostObjectTypeResolver;
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
    final protected function getRootSetMetaOnCategoryInputObjectTypeResolver(): AbstractSetMetaOnCategoryInputObjectTypeResolver
    {
        if ($this->rootSetMetaOnCategoryInputObjectTypeResolver === null) {
            /** @var RootSetMetaOnCategoryInputObjectTypeResolver */
            $rootSetMetaOnCategoryInputObjectTypeResolver = $this->instanceManager->getInstance(RootSetMetaOnCategoryInputObjectTypeResolver::class);
            $this->rootSetMetaOnCategoryInputObjectTypeResolver = $rootSetMetaOnCategoryInputObjectTypeResolver;
        }
        return $this->rootSetMetaOnCategoryInputObjectTypeResolver;
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
    final protected function getRootSetMetaOnCategoryMutationPayloadObjectTypeResolver(): RootSetMetaOnCategoryMutationPayloadObjectTypeResolver
    {
        if ($this->rootSetMetaOnCategoryMutationPayloadObjectTypeResolver === null) {
            /** @var RootSetMetaOnCategoryMutationPayloadObjectTypeResolver */
            $rootSetMetaOnCategoryMutationPayloadObjectTypeResolver = $this->instanceManager->getInstance(RootSetMetaOnCategoryMutationPayloadObjectTypeResolver::class);
            $this->rootSetMetaOnCategoryMutationPayloadObjectTypeResolver = $rootSetMetaOnCategoryMutationPayloadObjectTypeResolver;
        }
        return $this->rootSetMetaOnCategoryMutationPayloadObjectTypeResolver;
    }

    public function getCustomPostObjectTypeResolver(): CustomPostObjectTypeResolverInterface
    {
        return $this->getGenericCustomPostObjectTypeResolver();
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

    public function getCustomPostSetCategoriesInputObjectTypeResolver(): AbstractSetMetaOnCategoryInputObjectTypeResolver
    {
        return $this->getRootSetMetaOnCategoryInputObjectTypeResolver();
    }

    public function getPayloadableSetCategoriesMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableSetMetaOnCategoryMutationResolver();
    }

    public function getPayloadableSetCategoriesBulkOperationMutationResolver(): MutationResolverInterface
    {
        return $this->getPayloadableSetMetaOnCategoryBulkOperationMutationResolver();
    }

    protected function getRootSetCategoriesMutationPayloadObjectTypeResolver(): ConcreteTypeResolverInterface
    {
        return $this->getRootSetMetaOnCategoryMutationPayloadObjectTypeResolver();
    }

    protected function getEntityName(): string
    {
        return $this->__('custom post', 'post-category-mutations');
    }

    protected function getSetCategoriesFieldName(): string
    {
        return 'setMetaOnCategory';
    }

    protected function getBulkOperationSetCategoriesFieldName(): string
    {
        return 'setMetaOnCategories';
    }
}
