<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTags\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;
use PoPCMSSchema\Tags\FieldResolvers\ObjectType\AbstractCustomPostQueryableObjectTypeFieldResolver;
use PoPCMSSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPCMSSchema\Tags\TypeResolvers\ObjectType\TagObjectTypeResolverInterface;

class PostQueryableObjectTypeFieldResolver extends AbstractCustomPostQueryableObjectTypeFieldResolver
{
    private ?PostTagTypeAPIInterface $postTagTypeAPI = null;
    private ?PostTagObjectTypeResolver $postTagObjectTypeResolver = null;

    final public function setPostTagTypeAPI(PostTagTypeAPIInterface $postTagTypeAPI): void
    {
        $this->postTagTypeAPI = $postTagTypeAPI;
    }
    final protected function getPostTagTypeAPI(): PostTagTypeAPIInterface
    {
        return $this->postTagTypeAPI ??= $this->instanceManager->getInstance(PostTagTypeAPIInterface::class);
    }
    final public function setPostTagObjectTypeResolver(PostTagObjectTypeResolver $postTagObjectTypeResolver): void
    {
        $this->postTagObjectTypeResolver = $postTagObjectTypeResolver;
    }
    final protected function getPostTagObjectTypeResolver(): PostTagObjectTypeResolver
    {
        return $this->postTagObjectTypeResolver ??= $this->instanceManager->getInstance(PostTagObjectTypeResolver::class);
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostObjectTypeResolver::class,
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'tags' => $this->__('Tags added to this post', 'pop-post-tags'),
            'tagCount' => $this->__('Number of tags added to this post', 'pop-post-tags'),
            'tagNames' => $this->__('Names of the tags added to this post', 'pop-post-tags'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getTagTypeAPI(): TagTypeAPIInterface
    {
        return $this->getPostTagTypeAPI();
    }

    public function getTagTypeResolver(): TagObjectTypeResolverInterface
    {
        return $this->getPostTagObjectTypeResolver();
    }
}
