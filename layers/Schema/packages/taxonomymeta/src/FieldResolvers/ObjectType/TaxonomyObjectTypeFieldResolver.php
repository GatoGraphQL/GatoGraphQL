<?php

declare(strict_types=1);

namespace PoPSchema\TaxonomyMeta\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\Meta\FieldResolvers\InterfaceType\WithMetaInterfaceTypeFieldResolver;
use PoPSchema\Taxonomies\TypeResolvers\ObjectType\AbstractTaxonomyObjectTypeResolver;
use PoPSchema\TaxonomyMeta\Facades\TaxonomyMetaTypeAPIFacade;

class TaxonomyObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractTaxonomyObjectTypeResolver::class,
        ];
    }

    public function getImplementedInterfaceTypeFieldResolverClasses(): array
    {
        return [
            WithMetaInterfaceTypeFieldResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'metaValue',
            'metaValues',
        ];
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $taxonomyMetaAPI = TaxonomyMetaTypeAPIFacade::getInstance();
        $taxonomy = $resultItem;
        switch ($fieldName) {
            case 'metaValue':
            case 'metaValues':
                return $taxonomyMetaAPI->getTaxonomyTermMeta(
                    $objectTypeResolver->getID($taxonomy),
                    $fieldArgs['key'],
                    $fieldName === 'metaValue'
                );
        }

        return parent::resolveValue($objectTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
