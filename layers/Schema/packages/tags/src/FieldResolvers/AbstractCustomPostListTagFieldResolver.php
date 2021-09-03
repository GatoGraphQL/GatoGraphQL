<?php

declare(strict_types=1);

namespace PoPSchema\Tags\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPSchema\CustomPosts\FieldResolvers\AbstractCustomPostListFieldResolver;
use PoPSchema\Tags\ComponentContracts\TagAPIRequestedContractTrait;

abstract class AbstractCustomPostListTagFieldResolver extends AbstractCustomPostListFieldResolver
{
    use TagAPIRequestedContractTrait;

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'customPosts' => $this->translationAPI->__('Custom posts which contain this tag', 'pop-tags'),
            'customPostCount' => $this->translationAPI->__('Number of custom posts which contain this tag', 'pop-tags'),
            'customPostsForAdmin' => $this->translationAPI->__('[Unrestricted] Custom posts which contain this tag', 'pop-tags'),
            'customPostCountForAdmin' => $this->translationAPI->__('[Unrestricted] Number of custom posts which contain this tag', 'pop-tags'),
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

        $tag = $resultItem;
        switch ($fieldName) {
            case 'customPosts':
            case 'customPostCount':
            case 'customPostsForAdmin':
            case 'customPostCountForAdmin':
                $query[$this->getQueryProperty()] = [$typeResolver->getID($tag)];
                break;
        }

        return $query;
    }
}
