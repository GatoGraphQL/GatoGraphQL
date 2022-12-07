<?php

declare(strict_types=1);

namespace PoPCMSSchema\Posts\ConditionalOnModule\Comments\FieldResolvers\ObjectType;

use PoPCMSSchema\Comments\FieldResolvers\ObjectType\AbstractCommentableCustomPostObjectTypeFieldResolver;
use PoPCMSSchema\Posts\TypeAPIs\PostTypeAPIInterface;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class CommentablePostObjectTypeFieldResolver extends AbstractCommentableCustomPostObjectTypeFieldResolver
{
    private ?PostTypeAPIInterface $postTypeAPI = null;

    final public function setPostTypeAPI(PostTypeAPIInterface $postTypeAPI): void
    {
        $this->postTypeAPI = $postTypeAPI;
    }
    final protected function getPostTypeAPI(): PostTypeAPIInterface
    {
        /** @var PostTypeAPIInterface */
        return $this->postTypeAPI ??= $this->instanceManager->getInstance(PostTypeAPIInterface::class);
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
}
