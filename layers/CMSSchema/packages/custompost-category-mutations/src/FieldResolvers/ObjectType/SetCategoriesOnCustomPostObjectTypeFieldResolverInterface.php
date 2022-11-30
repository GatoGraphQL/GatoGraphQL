<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\InputObjectType\AbstractSetCategoriesOnCustomPostFilterInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

interface SetCategoriesOnCustomPostObjectTypeFieldResolverInterface
{
    public function getCustomPostObjectTypeResolver(): CustomPostObjectTypeResolverInterface;
    public function getCategoryTypeResolver(): CategoryObjectTypeResolverInterface;
    public function getSetCategoriesMutationResolver(): MutationResolverInterface;
    public function getCustomPostSetCategoriesFilterInputObjectTypeResolver(): AbstractSetCategoriesOnCustomPostFilterInputObjectTypeResolver;
    public function getPayloadableSetCategoriesMutationResolver(): MutationResolverInterface;
}
