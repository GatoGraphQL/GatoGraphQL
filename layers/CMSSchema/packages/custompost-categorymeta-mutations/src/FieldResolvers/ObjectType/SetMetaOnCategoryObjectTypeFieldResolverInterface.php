<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\InputObjectType\AbstractSetMetaOnCategoryInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

interface SetMetaOnCategoryObjectTypeFieldResolverInterface
{
    public function getCategoryObjectTypeResolver(): CustomPostObjectTypeResolverInterface;
    public function getCategoryTypeResolver(): CategoryObjectTypeResolverInterface;
    public function getSetCategoriesMutationResolver(): MutationResolverInterface;
    public function getSetCategoriesBulkOperationMutationResolver(): MutationResolverInterface;
    public function getCategorySetMetaInputObjectTypeResolver(): AbstractSetMetaOnCategoryInputObjectTypeResolver;
    public function getPayloadableSetCategoriesMutationResolver(): MutationResolverInterface;
    public function getPayloadableSetCategoriesBulkOperationMutationResolver(): MutationResolverInterface;
}
