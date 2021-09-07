<?php

declare(strict_types=1);

namespace PoPSchema\TaxonomyMeta\FieldResolvers;

use PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceSchemaDefinitionResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\FieldResolvers\FieldSchemaDefinitionResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPSchema\Meta\FieldInterfaceResolvers\WithMetaFieldInterfaceResolver;
use PoPSchema\Taxonomies\TypeResolvers\Object\AbstractTaxonomyTypeResolver;
use PoPSchema\TaxonomyMeta\Facades\TaxonomyMetaTypeAPIFacade;

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
     * Get the Schema Definition from the Interface
     */
    protected function doGetSchemaDefinitionResolver(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $fieldName
    ): FieldSchemaDefinitionResolverInterface | FieldInterfaceSchemaDefinitionResolverInterface {

        switch ($fieldName) {
            case 'metaValue':
            case 'metaValues':
                /** @var WithMetaFieldInterfaceResolver */
                $resolver = $this->instanceManager->getInstance(WithMetaFieldInterfaceResolver::class);
                return $resolver;
        }

        return parent::doGetSchemaDefinitionResolver($relationalTypeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        RelationalTypeResolverInterface $relationalTypeResolver,
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
                    $relationalTypeResolver->getID($taxonomy),
                    $fieldArgs['key'],
                    $fieldName === 'metaValue'
                );
        }

        return parent::resolveValue($relationalTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
