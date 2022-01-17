<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\Tags\ComponentContracts\TagAPIRequestedContractObjectTypeFieldResolverInterface;
use PoPCMSSchema\Taxonomies\FieldResolvers\ObjectType\AbstractCustomPostListTaxonomyObjectTypeFieldResolver;

abstract class AbstractCustomPostListTagObjectTypeFieldResolver extends AbstractCustomPostListTaxonomyObjectTypeFieldResolver implements TagAPIRequestedContractObjectTypeFieldResolverInterface
{
    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'customPosts' => $this->__('Custom posts which contain this tag', 'pop-tags'),
            'customPostCount' => $this->__('Number of custom posts which contain this tag', 'pop-tags'),
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
                $query[$this->getQueryProperty()] = [$objectTypeResolver->getID($tag)];
                break;
        }

        return $query;
    }
}
