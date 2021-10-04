<?php

declare(strict_types=1);

namespace PoPSchema\TaxonomyMeta\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\Meta\FieldResolvers\InterfaceType\WithMetaInterfaceTypeFieldResolver;
use PoPSchema\Taxonomies\TypeResolvers\ObjectType\AbstractTaxonomyObjectTypeResolver;
use PoPSchema\TaxonomyMeta\TypeAPIs\TaxonomyMetaTypeAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

class TaxonomyObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    protected TaxonomyMetaTypeAPIInterface $taxonomyMetaAPI;
    protected WithMetaInterfaceTypeFieldResolver $withMetaInterfaceTypeFieldResolver;

    #[Required]
    final public function autowireTaxonomyObjectTypeFieldResolver(
        TaxonomyMetaTypeAPIInterface $taxonomyMetaAPI,
        WithMetaInterfaceTypeFieldResolver $withMetaInterfaceTypeFieldResolver,
    ): void {
        $this->taxonomyMetaAPI = $taxonomyMetaAPI;
        $this->withMetaInterfaceTypeFieldResolver = $withMetaInterfaceTypeFieldResolver;
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractTaxonomyObjectTypeResolver::class,
        ];
    }

    public function getImplementedInterfaceTypeFieldResolvers(): array
    {
        return [
            $this->withMetaInterfaceTypeFieldResolver,
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
        object $object,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $taxonomy = $object;
        switch ($fieldName) {
            case 'metaValue':
            case 'metaValues':
                return $this->taxonomyMetaAPI->getTaxonomyTermMeta(
                    $objectTypeResolver->getID($taxonomy),
                    $fieldArgs['key'],
                    $fieldName === 'metaValue'
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
