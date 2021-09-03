<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPSchema\Posts\FieldResolvers\AbstractPostFieldResolver;
use PoPSchema\PostTags\TypeResolvers\PostTagTypeResolver;

class PostTagListFieldResolver extends AbstractPostFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(PostTagTypeResolver::class);
    }

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'posts' => $this->translationAPI->__('Posts which contain this tag', 'pop-taxonomies'),
            'postCount' => $this->translationAPI->__('Number of posts which contain this tag', 'pop-taxonomies'),
            'postsForAdmin' => $this->translationAPI->__('[Unrestricted] Posts which contain this tag', 'pop-taxonomies'),
            'postCountForAdmin' => $this->translationAPI->__('[Unrestricted] Number of posts which contain this tag', 'pop-taxonomies'),
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

        $tag = $resultItem;
        switch ($fieldName) {
            case 'posts':
            case 'postCount':
            case 'postsForAdmin':
            case 'postCountForAdmin':
                $query['tag-ids'] = [$relationalTypeResolver->getID($tag)];
                break;
        }

        return $query;
    }
}
