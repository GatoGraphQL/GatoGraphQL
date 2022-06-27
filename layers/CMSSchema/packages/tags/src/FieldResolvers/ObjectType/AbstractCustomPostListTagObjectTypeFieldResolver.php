<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoPCMSSchema\Tags\ModuleContracts\TagAPIRequestedContractObjectTypeFieldResolverInterface;
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
     * @return array<string, mixed>
     */
    protected function getQuery(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldInterface $field,
    ): array {
        $query = parent::getQuery($objectTypeResolver, $object, $field);

        $tag = $object;
        switch ($field->getName()) {
            case 'customPosts':
            case 'customPostCount':
                $query[$this->getQueryProperty()] = [$objectTypeResolver->getID($tag)];
                break;
        }

        return $query;
    }
}
