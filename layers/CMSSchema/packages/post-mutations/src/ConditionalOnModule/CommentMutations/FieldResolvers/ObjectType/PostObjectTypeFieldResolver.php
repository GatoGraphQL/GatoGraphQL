<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\ConditionalOnModule\CommentMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\ConditionalOnModule\Users\FieldResolvers\ObjectType\AbstractAddCommentToCustomPostObjectTypeFieldResolver;
use PoPCMSSchema\Posts\TypeAPIs\PostTypeAPIInterface;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

/**
 * Please notice: It extends a class under ConditionalOnModule/Users/
 * but there's no need to do the same, as Users will already exist
 * for Mutations packages
 */
class PostObjectTypeFieldResolver extends AbstractAddCommentToCustomPostObjectTypeFieldResolver
{
    private ?PostTypeAPIInterface $postTypeAPI = null;

    final protected function getPostTypeAPI(): PostTypeAPIInterface
    {
        if ($this->postTypeAPI === null) {
            /** @var PostTypeAPIInterface */
            $postTypeAPI = $this->instanceManager->getInstance(PostTypeAPIInterface::class);
            $this->postTypeAPI = $postTypeAPI;
        }
        return $this->postTypeAPI;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostObjectTypeResolver::class,
        ];
    }

    protected function getCustomPostType(): string
    {
        return $this->getPostTypeAPI()->getPostCustomPostType();
    }

    public function getAddCommentFieldDescription(): string
    {
        return $this->__('Add a comment to the post', 'post-mutations');
    }
}
