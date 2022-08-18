<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategories\FieldResolvers\ObjectType;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver;
use PoPCMSSchema\Posts\FieldResolvers\ObjectType\AbstractPostObjectTypeFieldResolver;

class PostCategoryListObjectTypeFieldResolver extends AbstractPostObjectTypeFieldResolver
{
    /**
     * @return array<class-string<\PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostCategoryObjectTypeResolver::class,
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'posts' => $this->__('Posts which contain this category', 'post-categories'),
            'postCount' => $this->__('Number of posts which contain this category', 'post-categories'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return array<string,mixed>
     */
    protected function getQuery(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
    ): array {
        $query = parent::getQuery($objectTypeResolver, $object, $fieldDataAccessor);

        $category = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'posts':
            case 'postCount':
                $query['category-ids'] = [$objectTypeResolver->getID($category)];
                break;
        }

        return $query;
    }
}
