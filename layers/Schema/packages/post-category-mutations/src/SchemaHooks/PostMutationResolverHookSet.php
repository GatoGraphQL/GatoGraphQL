<?php

declare(strict_types=1);

namespace PoPSchema\PostCategoryMutations\SchemaHooks;

use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\MutationRootObjectTypeResolver;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoPSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;
use PoPSchema\CustomPostCategoryMutations\Hooks\AbstractCustomPostMutationResolverHookSet;
use PoPSchema\CustomPostCategoryMutations\TypeAPIs\CustomPostCategoryTypeMutationAPIInterface;
use PoPSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver;
use PoPSchema\PostCategoryMutations\TypeAPIs\PostCategoryTypeMutationAPIInterface;
use PoPSchema\PostMutations\SchemaHooks\PostMutationResolverHookSetTrait;
use PoPSchema\Posts\TypeAPIs\PostTypeAPIInterface;
use PoPSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;

class PostMutationResolverHookSet extends AbstractCustomPostMutationResolverHookSet
{
    use PostMutationResolverHookSetTrait;

    private ?PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver = null;
    private ?PostTypeAPIInterface $postTypeAPI = null;
    private ?RootObjectTypeResolver $rootObjectTypeResolver = null;
    private ?MutationRootObjectTypeResolver $mutationRootObjectTypeResolver = null;
    private ?PostObjectTypeResolver $postObjectTypeResolver = null;
    private ?PostCategoryTypeMutationAPIInterface $postCategoryTypeMutationAPIInterface = null;

    final public function setPostCategoryObjectTypeResolver(PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver): void
    {
        $this->postCategoryObjectTypeResolver = $postCategoryObjectTypeResolver;
    }
    final protected function getPostCategoryObjectTypeResolver(): PostCategoryObjectTypeResolver
    {
        return $this->postCategoryObjectTypeResolver ??= $this->instanceManager->getInstance(PostCategoryObjectTypeResolver::class);
    }
    final public function setPostTypeAPI(PostTypeAPIInterface $postTypeAPI): void
    {
        $this->postTypeAPI = $postTypeAPI;
    }
    final protected function getPostTypeAPI(): PostTypeAPIInterface
    {
        return $this->postTypeAPI ??= $this->instanceManager->getInstance(PostTypeAPIInterface::class);
    }
    final public function setRootObjectTypeResolver(RootObjectTypeResolver $rootObjectTypeResolver): void
    {
        $this->rootObjectTypeResolver = $rootObjectTypeResolver;
    }
    final protected function getRootObjectTypeResolver(): RootObjectTypeResolver
    {
        return $this->rootObjectTypeResolver ??= $this->instanceManager->getInstance(RootObjectTypeResolver::class);
    }
    final public function setMutationRootObjectTypeResolver(MutationRootObjectTypeResolver $mutationRootObjectTypeResolver): void
    {
        $this->mutationRootObjectTypeResolver = $mutationRootObjectTypeResolver;
    }
    final protected function getMutationRootObjectTypeResolver(): MutationRootObjectTypeResolver
    {
        return $this->mutationRootObjectTypeResolver ??= $this->instanceManager->getInstance(MutationRootObjectTypeResolver::class);
    }
    final public function setPostObjectTypeResolver(PostObjectTypeResolver $postObjectTypeResolver): void
    {
        $this->postObjectTypeResolver = $postObjectTypeResolver;
    }
    final protected function getPostObjectTypeResolver(): PostObjectTypeResolver
    {
        return $this->postObjectTypeResolver ??= $this->instanceManager->getInstance(PostObjectTypeResolver::class);
    }
    final public function setPostCategoryTypeMutationAPI(PostCategoryTypeMutationAPIInterface $postCategoryTypeMutationAPIInterface): void
    {
        $this->postCategoryTypeMutationAPIInterface = $postCategoryTypeMutationAPIInterface;
    }
    final protected function getPostCategoryTypeMutationAPI(): PostCategoryTypeMutationAPIInterface
    {
        return $this->postCategoryTypeMutationAPIInterface ??= $this->instanceManager->getInstance(PostCategoryTypeMutationAPIInterface::class);
    }

    protected function getCustomPostType(): string
    {
        return $this->getPostTypeAPI()->getPostCustomPostType();
    }

    protected function getCategoryTypeResolver(): CategoryObjectTypeResolverInterface
    {
        return $this->getPostCategoryObjectTypeResolver();
    }

    protected function getCustomPostCategoryTypeMutationAPI(): CustomPostCategoryTypeMutationAPIInterface
    {
        return $this->getPostCategoryTypeMutationAPI();
    }
}
