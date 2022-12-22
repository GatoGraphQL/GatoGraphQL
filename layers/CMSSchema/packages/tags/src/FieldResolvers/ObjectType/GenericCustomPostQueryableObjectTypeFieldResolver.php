<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\GenericCustomPostObjectTypeResolver;
use PoPCMSSchema\Tags\FieldResolvers\ObjectType\AbstractCustomPostQueryableObjectTypeFieldResolver;
use PoPCMSSchema\Tags\TypeAPIs\QueryableTagTypeAPIInterface;
use PoPCMSSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPCMSSchema\Tags\TypeResolvers\InputObjectType\GenericCustomPostTagsFilterInputObjectTypeResolver;
use PoPCMSSchema\Tags\TypeResolvers\ObjectType\GenericTagObjectTypeResolver;
use PoPCMSSchema\Tags\TypeResolvers\ObjectType\TagObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericCustomPostQueryableObjectTypeFieldResolver extends AbstractCustomPostQueryableObjectTypeFieldResolver
{
    private ?QueryableTagTypeAPIInterface $queryableTagTypeAPI = null;
    private ?GenericTagObjectTypeResolver $genericTagObjectTypeResolver = null;
    private ?GenericCustomPostTagsFilterInputObjectTypeResolver $genericCustomPostTagsFilterInputObjectTypeResolver = null;

    final public function setQueryableTagTypeAPI(QueryableTagTypeAPIInterface $queryableTagTypeAPI): void
    {
        $this->queryableTagTypeAPI = $queryableTagTypeAPI;
    }
    final protected function getQueryableTagTypeAPI(): QueryableTagTypeAPIInterface
    {
        /** @var QueryableTagTypeAPIInterface */
        return $this->queryableTagTypeAPI ??= $this->instanceManager->getInstance(QueryableTagTypeAPIInterface::class);
    }
    final public function setGenericTagObjectTypeResolver(GenericTagObjectTypeResolver $genericTagObjectTypeResolver): void
    {
        $this->genericTagObjectTypeResolver = $genericTagObjectTypeResolver;
    }
    final protected function getGenericTagObjectTypeResolver(): GenericTagObjectTypeResolver
    {
        /** @var GenericTagObjectTypeResolver */
        return $this->genericTagObjectTypeResolver ??= $this->instanceManager->getInstance(GenericTagObjectTypeResolver::class);
    }
    final public function setGenericCustomPostTagsFilterInputObjectTypeResolver(GenericCustomPostTagsFilterInputObjectTypeResolver $genericCustomPostTagsFilterInputObjectTypeResolver): void
    {
        $this->genericCustomPostTagsFilterInputObjectTypeResolver = $genericCustomPostTagsFilterInputObjectTypeResolver;
    }
    final protected function getGenericCustomPostTagsFilterInputObjectTypeResolver(): GenericCustomPostTagsFilterInputObjectTypeResolver
    {
        /** @var GenericCustomPostTagsFilterInputObjectTypeResolver */
        return $this->genericCustomPostTagsFilterInputObjectTypeResolver ??= $this->instanceManager->getInstance(GenericCustomPostTagsFilterInputObjectTypeResolver::class);
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

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        /**
         * Add the "taxonomy" mandatory fieldArg
         */
        $fieldArgNameTypeResolvers = parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        if (in_array($fieldName, [
            'tags',
            'tagCount',
            'tagNames',
        ])) {
            $fieldArgNameTypeResolvers['filter'] = $this->getGenericCustomPostTagsFilterInputObjectTypeResolver();
        }
        return $fieldArgNameTypeResolvers;
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
