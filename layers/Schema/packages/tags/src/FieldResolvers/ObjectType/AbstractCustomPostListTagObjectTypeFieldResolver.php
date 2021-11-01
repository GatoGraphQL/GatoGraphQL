<?php

declare(strict_types=1);

namespace PoPSchema\Tags\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\CustomPosts\FieldResolvers\ObjectType\AbstractCustomPostListObjectTypeFieldResolver;
use PoPSchema\Tags\ComponentContracts\TagAPIRequestedContractObjectTypeFieldResolverInterface;

abstract class AbstractCustomPostListTagObjectTypeFieldResolver extends AbstractCustomPostListObjectTypeFieldResolver implements TagAPIRequestedContractObjectTypeFieldResolverInterface
{
    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'customPosts' => $this->getTranslationAPI()->__('Custom posts which contain this tag', 'pop-tags'),
            'customPostCount' => $this->getTranslationAPI()->__('Number of custom posts which contain this tag', 'pop-tags'),
            'customPostsForAdmin' => $this->getTranslationAPI()->__('[Unrestricted] Custom posts which contain this tag', 'pop-tags'),
            'customPostCountForAdmin' => $this->getTranslationAPI()->__('[Unrestricted] Number of custom posts which contain this tag', 'pop-tags'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
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
        array $fieldArgs
    ): array {
        $query = parent::getQuery($objectTypeResolver, $object, $fieldName, $fieldArgs);

        $tag = $object;
        switch ($fieldName) {
            case 'customPosts':
            case 'customPostCount':
            case 'customPostsForAdmin':
            case 'customPostCountForAdmin':
                $query[$this->getQueryProperty()] = [$objectTypeResolver->getID($tag)];
                break;
        }

        return $query;
    }
}
