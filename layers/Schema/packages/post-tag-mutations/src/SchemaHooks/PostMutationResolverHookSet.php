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

    protected PostObjectTypeResolver $postObjectTypeResolver;
    protected PostTypeAPIInterface $postTypeAPI;
    protected PostTagTypeMutationAPIInterface $postTagTypeMutationAPI;

    #[Required]
    final public function autowirePostMutationResolverHookSet(
        PostObjectTypeResolver $postObjectTypeResolver,
        PostTypeAPIInterface $postTypeAPI,
        PostTagTypeMutationAPIInterface $postTagTypeMutationAPI,
    ): void {
        $this->postObjectTypeResolver = $postObjectTypeResolver;
        $this->postTypeAPI = $postTypeAPI;
        $this->postTagTypeMutationAPI = $postTagTypeMutationAPI;
    }

    protected function getCustomPostObjectTypeResolver(): CustomPostObjectTypeResolverInterface
    {
        return $this->postObjectTypeResolver;
    }

    protected function getCustomPostType(): string
    {
        return $this->postTypeAPI->getPostCustomPostType();
    }

    protected function getCustomPostTagTypeMutationAPI(): CustomPostTagTypeMutationAPIInterface
    {
        return $this->postTagTypeMutationAPI;
    }
}
