<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\GenericCustomPostObjectTypeResolver;
use PoPCMSSchema\Tags\ComponentProcessors\TagFilterInputContainerComponentProcessor;
use PoPCMSSchema\Tags\FieldResolvers\ObjectType\AbstractCustomPostQueryableObjectTypeFieldResolver;
use PoPCMSSchema\Tags\TypeAPIs\QueryableTagTypeAPIInterface;
use PoPCMSSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPCMSSchema\Tags\TypeResolvers\ObjectType\GenericTagObjectTypeResolver;
use PoPCMSSchema\Tags\TypeResolvers\ObjectType\TagObjectTypeResolverInterface;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericCustomPostQueryableObjectTypeFieldResolver extends AbstractCustomPostQueryableObjectTypeFieldResolver
{
    private ?QueryableTagTypeAPIInterface $queryableTagTypeAPI = null;
    private ?GenericTagObjectTypeResolver $genericTagObjectTypeResolver = null;

    final protected function getQueryableTagTypeAPI(): QueryableTagTypeAPIInterface
    {
        if ($this->queryableTagTypeAPI === null) {
            /** @var QueryableTagTypeAPIInterface */
            $queryableTagTypeAPI = $this->instanceManager->getInstance(QueryableTagTypeAPIInterface::class);
            $this->queryableTagTypeAPI = $queryableTagTypeAPI;
        }
        return $this->queryableTagTypeAPI;
    }
    final protected function getGenericTagObjectTypeResolver(): GenericTagObjectTypeResolver
    {
        if ($this->genericTagObjectTypeResolver === null) {
            /** @var GenericTagObjectTypeResolver */
            $genericTagObjectTypeResolver = $this->instanceManager->getInstance(GenericTagObjectTypeResolver::class);
            $this->genericTagObjectTypeResolver = $genericTagObjectTypeResolver;
        }
        return $this->genericTagObjectTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            GenericCustomPostObjectTypeResolver::class,
        ];
    }

    public function getFieldFilterInputContainerComponent(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?Component
    {
        return match ($fieldName) {
            'tags',
            'tagCount',
            'tagNames'
                => new Component(
                    TagFilterInputContainerComponentProcessor::class,
                    TagFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_GENERICTAGS
                ),
            default
                => parent::getFieldFilterInputContainerComponent($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'tags' => $this->__('Tags added to this custom post', 'pop-post-tags'),
            'tagCount' => $this->__('Number of tags added to this custom post', 'pop-post-tags'),
            'tagNames' => $this->__('Names of the tags added to this custom post', 'pop-post-tags'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getTagTypeAPI(): TagTypeAPIInterface
    {
        return $this->getQueryableTagTypeAPI();
    }

    public function getTagTypeResolver(): TagObjectTypeResolverInterface
    {
        return $this->getGenericTagObjectTypeResolver();
    }
}
