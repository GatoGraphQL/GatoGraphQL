<?php

declare(strict_types=1);

namespace PoPSchema\PostTagMutations\Hooks;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;
use PoPSchema\CustomPostTagMutations\Hooks\AbstractCustomPostMutationResolverHookSet;
use PoPSchema\CustomPostTagMutations\TypeAPIs\CustomPostTagTypeMutationAPIInterface;
use PoPSchema\Posts\TypeAPIs\PostTypeAPIInterface;
use PoPSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPSchema\PostTagMutations\TypeAPIs\PostTagTypeMutationAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

class PostMutationResolverHookSet extends AbstractCustomPostMutationResolverHookSet
{
    protected RootObjectTypeResolver $rootObjectTypeResolver;
    protected PostObjectTypeResolver $postObjectTypeResolver;
    protected PostTypeAPIInterface $postTypeAPI;
    protected PostTagTypeMutationAPIInterface $postTagTypeMutationAPI;

    #[Required]
    public function autowirePostMutationResolverHookSet(
        RootObjectTypeResolver $rootObjectTypeResolver,
        PostObjectTypeResolver $postObjectTypeResolver,
        PostTypeAPIInterface $postTypeAPI,
        PostTagTypeMutationAPIInterface $postTagTypeMutationAPI,
    ): void {
        $this->rootObjectTypeResolver = $rootObjectTypeResolver;
        $this->postObjectTypeResolver = $postObjectTypeResolver;
        $this->postTypeAPI = $postTypeAPI;
        $this->postTagTypeMutationAPI = $postTagTypeMutationAPI;
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
