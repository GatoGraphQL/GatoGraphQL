<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType\AbstractSetMetaOnCategoryInputObjectTypeResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

interface SetMetaOnCategoryObjectTypeFieldResolverInterface
{
    public function getCategoryObjectTypeResolver(): CategoryObjectTypeResolverInterface;
    public function getCategoryTypeResolver(): CategoryObjectTypeResolverInterface;
    public function getSetMetaMutationResolver(): MutationResolverInterface;
    public function getSetMetaBulkOperationMutationResolver(): MutationResolverInterface;
    public function getCategorySetMetaInputObjectTypeResolver(): AbstractSetMetaOnCategoryInputObjectTypeResolver;
    public function getPayloadableSetMetaMutationResolver(): MutationResolverInterface;
    public function getPayloadableSetMetaBulkOperationMutationResolver(): MutationResolverInterface;
}
