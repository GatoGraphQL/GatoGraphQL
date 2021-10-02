<?php

declare(strict_types=1);

namespace PoPSchema\PostCategoryMutations\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoPSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;
use PoPSchema\CustomPostCategoryMutations\Hooks\AbstractCustomPostMutationResolverHookSet;
use PoPSchema\CustomPostCategoryMutations\TypeAPIs\CustomPostCategoryTypeMutationAPIInterface;
use PoPSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver;
use PoPSchema\PostCategoryMutations\Facades\PostCategoryTypeMutationAPIFacade;
use PoPSchema\Posts\TypeAPIs\PostTypeAPIInterface;
use PoPSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class PostMutationResolverHookSet extends AbstractCustomPostMutationResolverHookSet
{
    protected RootObjectTypeResolver $rootObjectTypeResolver;
    protected PostObjectTypeResolver $postObjectTypeResolver;
    protected PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver;
    protected PostTypeAPIInterface $postTypeAPI;

    #[Required]
    public function autowirePostMutationResolverHookSet(
        RootObjectTypeResolver $rootObjectTypeResolver,
        PostObjectTypeResolver $postObjectTypeResolver,
        PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver,
        PostTypeAPIInterface $postTypeAPI,
    ): void {
        $this->rootObjectTypeResolver = $rootObjectTypeResolver;
        $this->postObjectTypeResolver = $postObjectTypeResolver;
        $this->postCategoryObjectTypeResolver = $postCategoryObjectTypeResolver;
        $this->postTypeAPI = $postTypeAPI;
    }

    protected function mustAddSchemaFieldArgs(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
    ): bool {
        return 
            ($objectTypeResolver === $this->rootObjectTypeResolver && $fieldName === 'createPost')
            || ($objectTypeResolver === $this->rootObjectTypeResolver && $fieldName === 'updatePost')
            || ($objectTypeResolver === $this->postObjectTypeResolver && $fieldName === 'update');
    }

    protected function getCustomPostType(): string
    {
        return $this->postTypeAPI->getPostCustomPostType();
    }

    protected function getCategoryTypeResolver(): CategoryObjectTypeResolverInterface
    {
        return $this->postCategoryObjectTypeResolver;
    }

    protected function getCustomPostCategoryTypeMutationAPI(): CustomPostCategoryTypeMutationAPIInterface
    {
        return PostCategoryTypeMutationAPIFacade::getInstance();
    }
}
