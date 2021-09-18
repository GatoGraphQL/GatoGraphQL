<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostCategoryMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;

interface SetCategoriesOnCustomPostObjectTypeFieldResolverInterface
{
    public function getCustomPostTypeResolver(): CustomPostObjectTypeResolverInterface;
    public function getCategoryTypeResolver(): CategoryObjectTypeResolverInterface;
    public function getSetCategoriesMutationResolver(): MutationResolverInterface;
}
