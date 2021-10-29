<?php

declare(strict_types=1);

namespace PoPSchema\PostTagMutations\SchemaHooks;

use PoPSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;
use PoPSchema\CustomPostTagMutations\Hooks\AbstractCustomPostMutationResolverHookSet;
use PoPSchema\CustomPostTagMutations\TypeAPIs\CustomPostTagTypeMutationAPIInterface;
use PoPSchema\PostMutations\SchemaHooks\PostMutationResolverHookSetTrait;
use PoPSchema\Posts\TypeAPIs\PostTypeAPIInterface;
use PoPSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPSchema\PostTagMutations\TypeAPIs\PostTagTypeMutationAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

class PostMutationResolverHookSet extends AbstractCustomPostMutationResolverHookSet
{
    use PostMutationResolverHookSetTrait;

    private ?PostObjectTypeResolver $postObjectTypeResolver = null;
    private ?PostTypeAPIInterface $postTypeAPI = null;
    private ?PostTagTypeMutationAPIInterface $postTagTypeMutationAPI = null;

    public function setPostObjectTypeResolver(PostObjectTypeResolver $postObjectTypeResolver): void
    {
        $this->postObjectTypeResolver = $postObjectTypeResolver;
    }
    protected function getPostObjectTypeResolver(): PostObjectTypeResolver
    {
        return $this->postObjectTypeResolver ??= $this->instanceManager->getInstance(PostObjectTypeResolver::class);
    }
    public function setPostTypeAPI(PostTypeAPIInterface $postTypeAPI): void
    {
        $this->postTypeAPI = $postTypeAPI;
    }
    protected function getPostTypeAPI(): PostTypeAPIInterface
    {
        return $this->postTypeAPI ??= $this->instanceManager->getInstance(PostTypeAPIInterface::class);
    }
    public function setPostTagTypeMutationAPI(PostTagTypeMutationAPIInterface $postTagTypeMutationAPI): void
    {
        $this->postTagTypeMutationAPI = $postTagTypeMutationAPI;
    }
    protected function getPostTagTypeMutationAPI(): PostTagTypeMutationAPIInterface
    {
        return $this->postTagTypeMutationAPI ??= $this->instanceManager->getInstance(PostTagTypeMutationAPIInterface::class);
    }

    protected function getCustomPostObjectTypeResolver(): CustomPostObjectTypeResolverInterface
    {
        return $this->getPostObjectTypeResolver();
    }

    protected function getCustomPostType(): string
    {
        return $this->getPostTypeAPI()->getPostCustomPostType();
    }

    protected function getCustomPostTagTypeMutationAPI(): CustomPostTagTypeMutationAPIInterface
    {
        return $this->getPostTagTypeMutationAPI();
    }
}
