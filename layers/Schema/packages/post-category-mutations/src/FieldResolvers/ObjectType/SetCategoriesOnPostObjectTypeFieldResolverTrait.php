<?php

declare(strict_types=1);

namespace PoPSchema\PostCategoryMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;
use PoPSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver;
use PoPSchema\PostCategoryMutations\MutationResolvers\SetCategoriesOnPostMutationResolver;
use PoPSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

trait SetCategoriesOnPostObjectTypeFieldResolverTrait
{
    protected PostObjectTypeResolver $postObjectTypeResolver;
    protected SetCategoriesOnPostMutationResolver $setCategoriesOnPostMutationResolver;
    protected PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver;
    protected TranslationAPIInterface $translationAPI;

    #[Required]
    public function autowireSetCategoriesOnPostObjectTypeFieldResolverTrait(
        PostObjectTypeResolver $postObjectTypeResolver,
        SetCategoriesOnPostMutationResolver $setCategoriesOnPostMutationResolver,
        PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver,
        TranslationAPIInterface $translationAPI,
    ): void {
        $this->postObjectTypeResolver = $postObjectTypeResolver;
        $this->setCategoriesOnPostMutationResolver = $setCategoriesOnPostMutationResolver;
        $this->postCategoryObjectTypeResolver = $postCategoryObjectTypeResolver;
        $this->translationAPI = $translationAPI;
    }

    public function getCustomPostObjectTypeResolver(): CustomPostObjectTypeResolverInterface
    {
        return $this->postObjectTypeResolver;
    }

    public function getSetCategoriesMutationResolver(): MutationResolverInterface
    {
        return $this->setCategoriesOnPostMutationResolver;
    }

    public function getCategoryTypeResolver(): CategoryObjectTypeResolverInterface
    {
        return $this->postCategoryObjectTypeResolver;
    }

    protected function getEntityName(): string
    {
        return $this->translationAPI->__('post', 'post-category-mutations');
    }
}
