<?php

declare(strict_types=1);

namespace PoPSchema\Categories\FieldResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\CustomPosts\FieldResolvers\AbstractCustomPostListFieldResolver;
use PoPSchema\Categories\ComponentContracts\CategoryAPIRequestedContractTrait;

abstract class AbstractCustomPostListCategoryFieldResolver extends AbstractCustomPostListFieldResolver
{
    use CategoryAPIRequestedContractTrait;

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'customPosts' => $translationAPI->__('Custom posts which contain this category', 'pop-categories'),
            'customPostCount' => $translationAPI->__('Number of custom posts which contain this category', 'pop-categories'),
            'unrestrictedCustomPosts' => $translationAPI->__('[Unrestricted] Custom posts which contain this category', 'pop-categories'),
            'unrestrictedCustomPostCount' => $translationAPI->__('[Unrestricted] Number of custom posts which contain this category', 'pop-categories'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    abstract protected function getQueryProperty(): string;

    /**
     * @param array<string, mixed> $fieldArgs
     * @return array<string, mixed>
     */
    protected function getQuery(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = []
    ): array {
        $query = parent::getQuery($typeResolver, $resultItem, $fieldName, $fieldArgs);

        $category = $resultItem;
        switch ($fieldName) {
            case 'customPosts':
            case 'customPostCount':
            case 'unrestrictedCustomPosts':
            case 'unrestrictedCustomPostCount':
                $query[$this->getQueryProperty()] = [$typeResolver->getID($category)];
                break;
        }

        return $query;
    }
}
