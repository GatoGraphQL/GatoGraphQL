<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPSchema\Posts\FieldResolvers\AbstractPostFieldResolver;
use PoPSchema\PostCategories\TypeResolvers\PostCategoryTypeResolver;

class PostCategoryListFieldResolver extends AbstractPostFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(PostCategoryTypeResolver::class);
    }

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'posts' => $this->translationAPI->__('Posts which contain this category', 'post-categories'),
            'postCount' => $this->translationAPI->__('Number of posts which contain this category', 'post-categories'),
            'postsForAdmin' => $this->translationAPI->__('[Unrestricted] Posts which contain this category', 'post-categories'),
            'postCountForAdmin' => $this->translationAPI->__('[Unrestricted] Number of posts which contain this category', 'post-categories'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($relationalTypeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @return array<string, mixed>
     */
    protected function getQuery(
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = []
    ): array {
        $query = parent::getQuery($relationalTypeResolver, $resultItem, $fieldName, $fieldArgs);

        $category = $resultItem;
        switch ($fieldName) {
            case 'posts':
            case 'postCount':
            case 'postsForAdmin':
            case 'postCountForAdmin':
                $query['category-ids'] = [$relationalTypeResolver->getID($category)];
                break;
        }

        return $query;
    }
}
