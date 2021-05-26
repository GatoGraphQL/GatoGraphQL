<?php

declare(strict_types=1);

namespace PoPSchema\TaxonomyMeta\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\FieldResolvers\FieldSchemaDefinitionResolverInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\Meta\FieldInterfaceResolvers\WithMetaFieldInterfaceResolver;
use PoPSchema\TaxonomyMeta\Facades\TaxonomyMetaTypeAPIFacade;
use PoPSchema\Taxonomies\TypeResolvers\AbstractTaxonomyTypeResolver;

class TaxonomyFieldResolver extends AbstractDBDataFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return [
            AbstractTaxonomyTypeResolver::class,
        ];
    }

    public function getImplementedFieldInterfaceResolverClasses(): array
    {
        return [
            WithMetaFieldInterfaceResolver::class,
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
     * By returning `null`, the schema definition comes from the interface
     */
    public function getSchemaDefinitionResolver(TypeResolverInterface $typeResolver): ?FieldSchemaDefinitionResolverInterface
    {
        return null;
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
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
                    $typeResolver->getID($taxonomy),
                    $fieldArgs['key'],
                    $fieldName === 'metaValue'
                );
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
