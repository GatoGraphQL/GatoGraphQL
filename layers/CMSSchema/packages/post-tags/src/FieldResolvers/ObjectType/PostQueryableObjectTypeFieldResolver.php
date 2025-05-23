<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTags\FieldResolvers\ObjectType;

use PoPCMSSchema\PostTags\ComponentProcessors\PostTagFilterInputContainerComponentProcessor;
use PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPCMSSchema\Tags\FieldResolvers\ObjectType\AbstractCustomPostQueryableObjectTypeFieldResolver;
use PoPCMSSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPCMSSchema\Tags\TypeResolvers\ObjectType\TagObjectTypeResolverInterface;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class PostQueryableObjectTypeFieldResolver extends AbstractCustomPostQueryableObjectTypeFieldResolver
{
    private ?PostTagTypeAPIInterface $postTagTypeAPI = null;
    private ?PostTagObjectTypeResolver $postTagObjectTypeResolver = null;

    final protected function getPostTagTypeAPI(): PostTagTypeAPIInterface
    {
        if ($this->postTagTypeAPI === null) {
            /** @var PostTagTypeAPIInterface */
            $postTagTypeAPI = $this->instanceManager->getInstance(PostTagTypeAPIInterface::class);
            $this->postTagTypeAPI = $postTagTypeAPI;
        }
        return $this->postTagTypeAPI;
    }
    final protected function getPostTagObjectTypeResolver(): PostTagObjectTypeResolver
    {
        if ($this->postTagObjectTypeResolver === null) {
            /** @var PostTagObjectTypeResolver */
            $postTagObjectTypeResolver = $this->instanceManager->getInstance(PostTagObjectTypeResolver::class);
            $this->postTagObjectTypeResolver = $postTagObjectTypeResolver;
        }
        return $this->postTagObjectTypeResolver;
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

    public function getFieldFilterInputContainerComponent(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?Component
    {
        return match ($fieldName) {
            'tags',
            'tagNames',
            'tagCount'
                => new Component(
                    PostTagFilterInputContainerComponentProcessor::class,
                    PostTagFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_POSTTAGS
                ),
            default
                => parent::getFieldFilterInputContainerComponent($objectTypeResolver, $fieldName),
        };
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
