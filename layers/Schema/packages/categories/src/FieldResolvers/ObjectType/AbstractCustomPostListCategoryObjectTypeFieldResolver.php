<?php

declare(strict_types=1);

namespace PoPSchema\Categories\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\Categories\ComponentContracts\CategoryAPIRequestedContractInterface;
use PoPSchema\CustomPosts\FieldResolvers\ObjectType\AbstractCustomPostListObjectTypeFieldResolver;

abstract class AbstractCustomPostListCategoryObjectTypeFieldResolver extends AbstractCustomPostListObjectTypeFieldResolver implements CategoryAPIRequestedContractInterface
{
    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'customPosts' => $this->translationAPI->__('Custom posts which contain this category', 'pop-categories'),
            'customPostCount' => $this->translationAPI->__('Number of custom posts which contain this category', 'pop-categories'),
            'customPostsForAdmin' => $this->translationAPI->__('[Unrestricted] Custom posts which contain this category', 'pop-categories'),
            'customPostCountForAdmin' => $this->translationAPI->__('[Unrestricted] Number of custom posts which contain this category', 'pop-categories'),
            default => parent::getSchemaFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    abstract protected function getQueryProperty(): string;

    /**
     * @param array<string, mixed> $fieldArgs
     * @return array<string, mixed>
     */
    protected function getQuery(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = []
    ): array {
        $query = parent::getQuery($objectTypeResolver, $object, $fieldName, $fieldArgs);

        $category = $object;
        switch ($fieldName) {
            case 'customPosts':
            case 'customPostCount':
            case 'customPostsForAdmin':
            case 'customPostCountForAdmin':
                $query[$this->getQueryProperty()] = [$objectTypeResolver->getID($category)];
                break;
        }

        return $query;
    }
}
