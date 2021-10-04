<?php

declare(strict_types=1);

namespace PoPSchema\PostCategoryMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;
use PoPSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver;
use PoPSchema\PostCategoryMutations\MutationResolvers\SetCategoriesOnPostMutationResolver;
use PoPSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;

trait SetCategoriesOnPostObjectTypeFieldResolverTrait
{
    public function getCustomPostObjectTypeResolver(): CustomPostObjectTypeResolverInterface
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        return $instanceManager->getInstance(PostObjectTypeResolver::class);
    }

    public function getSetCategoriesMutationResolver(): MutationResolverInterface
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        return $instanceManager->getInstance(SetCategoriesOnPostMutationResolver::class);
    }

    public function getCategoryTypeResolver(): CategoryObjectTypeResolverInterface
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        return $instanceManager->getInstance(PostCategoryObjectTypeResolver::class);
    }

    protected function getEntityName(): string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('post', 'post-category-mutations');
    }
}
