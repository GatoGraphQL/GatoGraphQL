<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostCategoryMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;
use PoPSchema\CustomPostCategoryMutations\TypeResolvers\InputObjectType\AbstractSetCategoriesOnCustomPostFilterInputObjectTypeResolver;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;

interface SetCategoriesOnCustomPostObjectTypeFieldResolverInterface
{
    public function getCustomPostObjectTypeResolver(): CustomPostObjectTypeResolverInterface;
    public function getCategoryTypeResolver(): CategoryObjectTypeResolverInterface;
    public function getSetCategoriesMutationResolver(): MutationResolverInterface;
    public function getCustomPostSetCategoriesFilterInputObjectTypeResolver(): AbstractSetCategoriesOnCustomPostFilterInputObjectTypeResolver;
}
