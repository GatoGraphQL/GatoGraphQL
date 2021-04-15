<?php

declare(strict_types=1);

namespace PoPSchema\Tags\FieldResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\CustomPosts\FieldResolvers\AbstractCustomPostListFieldResolver;
use PoPSchema\Tags\ComponentContracts\TagAPIRequestedContractTrait;

abstract class AbstractCustomPostListTagFieldResolver extends AbstractCustomPostListFieldResolver
{
    use TagAPIRequestedContractTrait;

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'customPosts' => $translationAPI->__('Custom posts which contain this tag', 'pop-tags'),
            'customPostCount' => $translationAPI->__('Number of custom posts which contain this tag', 'pop-tags'),
            'adminCustomPosts' => $translationAPI->__('[Admin] Custom posts which contain this tag', 'pop-tags'),
            'adminCustomPostCount' => $translationAPI->__('[Admin] Number of custom posts which contain this tag', 'pop-tags'),
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

        $tag = $resultItem;
        switch ($fieldName) {
            case 'customPosts':
            case 'customPostCount':
            case 'adminCustomPosts':
            case 'adminCustomPostCount':
                $query[$this->getQueryProperty()] = [$typeResolver->getID($tag)];
                break;
        }

        return $query;
    }
}
