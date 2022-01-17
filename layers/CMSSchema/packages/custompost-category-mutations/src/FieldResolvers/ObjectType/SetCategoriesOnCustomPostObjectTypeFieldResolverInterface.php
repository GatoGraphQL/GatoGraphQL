<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPCMSSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\InputObjectType\AbstractSetCategoriesOnCustomPostFilterInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;

interface SetCategoriesOnCustomPostObjectTypeFieldResolverInterface
{
    public function getCustomPostObjectTypeResolver(): CustomPostObjectTypeResolverInterface;
    public function getCategoryTypeResolver(): CategoryObjectTypeResolverInterface;
    public function getSetCategoriesMutationResolver(): MutationResolverInterface;
    public function getCustomPostSetCategoriesFilterInputObjectTypeResolver(): AbstractSetCategoriesOnCustomPostFilterInputObjectTypeResolver;
}
