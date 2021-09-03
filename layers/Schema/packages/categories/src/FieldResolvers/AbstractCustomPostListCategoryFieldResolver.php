<?php

declare(strict_types=1);

namespace PoPSchema\Categories\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPSchema\CustomPosts\FieldResolvers\AbstractCustomPostListFieldResolver;
use PoPSchema\Categories\ComponentContracts\CategoryAPIRequestedContractTrait;

abstract class AbstractCustomPostListCategoryFieldResolver extends AbstractCustomPostListFieldResolver
{
    use CategoryAPIRequestedContractTrait;

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'customPosts' => $this->translationAPI->__('Custom posts which contain this category', 'pop-categories'),
            'customPostCount' => $this->translationAPI->__('Number of custom posts which contain this category', 'pop-categories'),
            'customPostsForAdmin' => $this->translationAPI->__('[Unrestricted] Custom posts which contain this category', 'pop-categories'),
            'customPostCountForAdmin' => $this->translationAPI->__('[Unrestricted] Number of custom posts which contain this category', 'pop-categories'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    abstract protected function getQueryProperty(): string;

    /**
     * @param array<string, mixed> $fieldArgs
     * @return array<string, mixed>
     */
    protected function getQuery(
        RelationalTypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = []
    ): array {
        $query = parent::getQuery($typeResolver, $resultItem, $fieldName, $fieldArgs);

        $category = $resultItem;
        switch ($fieldName) {
            case 'customPosts':
            case 'customPostCount':
            case 'customPostsForAdmin':
            case 'customPostCountForAdmin':
                $query[$this->getQueryProperty()] = [$typeResolver->getID($category)];
                break;
        }

        return $query;
    }
}
