<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\FieldResolvers\ObjectType;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoPCMSSchema\Categories\ModuleContracts\CategoryAPIRequestedContractObjectTypeFieldResolverInterface;
use PoPCMSSchema\Taxonomies\FieldResolvers\ObjectType\AbstractCustomPostListTaxonomyObjectTypeFieldResolver;

abstract class AbstractCustomPostListCategoryObjectTypeFieldResolver extends AbstractCustomPostListTaxonomyObjectTypeFieldResolver implements CategoryAPIRequestedContractObjectTypeFieldResolverInterface
{
    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'customPosts' => $this->__('Custom posts which contain this category', 'pop-categories'),
            'customPostCount' => $this->__('Number of custom posts which contain this category', 'pop-categories'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    abstract protected function getQueryProperty(): string;

    /**
     * @return array<string, mixed>
     */
    protected function getQuery(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
    ): array {
        $query = parent::getQuery($objectTypeResolver, $object, $fieldDataAccessor);

        $category = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'customPosts':
            case 'customPostCount':
                $query[$this->getQueryProperty()] = [$objectTypeResolver->getID($category)];
                break;
        }

        return $query;
    }
}
