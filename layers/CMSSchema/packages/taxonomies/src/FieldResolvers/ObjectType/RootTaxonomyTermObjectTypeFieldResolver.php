<?php

declare(strict_types=1);

namespace PoPCMSSchema\Taxonomies\FieldResolvers\ObjectType;

use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPCMSSchema\Taxonomies\TypeAPIs\QueryableTaxonomyTermTypeAPIInterface;
use PoPCMSSchema\Taxonomies\TypeResolvers\EnumType\TaxonomyTermTaxonomyEnumStringScalarTypeResolver;
use PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\RootTaxonomyTermsFilterInputObjectTypeResolver;
use PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\TaxonomyTermByOneofInputObjectTypeResolver;
use PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\TaxonomyTermPaginationInputObjectTypeResolver;
use PoPCMSSchema\Taxonomies\TypeResolvers\UnionType\TaxonomyTermUnionTypeResolver;
use PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\TaxonomySortInputObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class RootTaxonomyTermObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;

    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?TaxonomyTermUnionTypeResolver $taxonomyTermUnionTypeResolver = null;
    private ?TaxonomyTermByOneofInputObjectTypeResolver $taxonomyTermByOneofInputObjectTypeResolver = null;
    private ?TaxonomyTermTaxonomyEnumStringScalarTypeResolver $taxonomyTermTaxonomyEnumStringScalarTypeResolver = null;
    private ?TaxonomyTermPaginationInputObjectTypeResolver $taxonomyTermPaginationInputObjectTypeResolver = null;
    private ?TaxonomySortInputObjectTypeResolver $taxonomySortInputObjectTypeResolver = null;
    private ?RootTaxonomyTermsFilterInputObjectTypeResolver $rootTaxonomyTermsFilterInputObjectTypeResolver = null;
    private ?QueryableTaxonomyTermTypeAPIInterface $queryableTaxonomyTermTypeAPI = null;

    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        if ($this->intScalarTypeResolver === null) {
            /** @var IntScalarTypeResolver */
            $intScalarTypeResolver = $this->instanceManager->getInstance(IntScalarTypeResolver::class);
            $this->intScalarTypeResolver = $intScalarTypeResolver;
        }
        return $this->intScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }
    final protected function getTaxonomyTermUnionTypeResolver(): TaxonomyTermUnionTypeResolver
    {
        if ($this->taxonomyTermUnionTypeResolver === null) {
            /** @var TaxonomyTermUnionTypeResolver */
            $taxonomyTermUnionTypeResolver = $this->instanceManager->getInstance(TaxonomyTermUnionTypeResolver::class);
            $this->taxonomyTermUnionTypeResolver = $taxonomyTermUnionTypeResolver;
        }
        return $this->taxonomyTermUnionTypeResolver;
    }
    final protected function getTaxonomyTermByOneofInputObjectTypeResolver(): TaxonomyTermByOneofInputObjectTypeResolver
    {
        if ($this->taxonomyTermByOneofInputObjectTypeResolver === null) {
            /** @var TaxonomyTermByOneofInputObjectTypeResolver */
            $taxonomyTermByOneofInputObjectTypeResolver = $this->instanceManager->getInstance(TaxonomyTermByOneofInputObjectTypeResolver::class);
            $this->taxonomyTermByOneofInputObjectTypeResolver = $taxonomyTermByOneofInputObjectTypeResolver;
        }
        return $this->taxonomyTermByOneofInputObjectTypeResolver;
    }
    final protected function getTaxonomyTermTaxonomyEnumStringScalarTypeResolver(): TaxonomyTermTaxonomyEnumStringScalarTypeResolver
    {
        if ($this->taxonomyTermTaxonomyEnumStringScalarTypeResolver === null) {
            /** @var TaxonomyTermTaxonomyEnumStringScalarTypeResolver */
            $taxonomyTermTaxonomyEnumStringScalarTypeResolver = $this->instanceManager->getInstance(TaxonomyTermTaxonomyEnumStringScalarTypeResolver::class);
            $this->taxonomyTermTaxonomyEnumStringScalarTypeResolver = $taxonomyTermTaxonomyEnumStringScalarTypeResolver;
        }
        return $this->taxonomyTermTaxonomyEnumStringScalarTypeResolver;
    }
    final protected function getTaxonomyTermPaginationInputObjectTypeResolver(): TaxonomyTermPaginationInputObjectTypeResolver
    {
        if ($this->taxonomyTermPaginationInputObjectTypeResolver === null) {
            /** @var TaxonomyTermPaginationInputObjectTypeResolver */
            $taxonomyTermPaginationInputObjectTypeResolver = $this->instanceManager->getInstance(TaxonomyTermPaginationInputObjectTypeResolver::class);
            $this->taxonomyTermPaginationInputObjectTypeResolver = $taxonomyTermPaginationInputObjectTypeResolver;
        }
        return $this->taxonomyTermPaginationInputObjectTypeResolver;
    }
    final protected function getTaxonomySortInputObjectTypeResolver(): TaxonomySortInputObjectTypeResolver
    {
        if ($this->taxonomySortInputObjectTypeResolver === null) {
            /** @var TaxonomySortInputObjectTypeResolver */
            $taxonomySortInputObjectTypeResolver = $this->instanceManager->getInstance(TaxonomySortInputObjectTypeResolver::class);
            $this->taxonomySortInputObjectTypeResolver = $taxonomySortInputObjectTypeResolver;
        }
        return $this->taxonomySortInputObjectTypeResolver;
    }
    final protected function getRootTaxonomyTermsFilterInputObjectTypeResolver(): RootTaxonomyTermsFilterInputObjectTypeResolver
    {
        if ($this->rootTaxonomyTermsFilterInputObjectTypeResolver === null) {
            /** @var RootTaxonomyTermsFilterInputObjectTypeResolver */
            $rootTaxonomyTermsFilterInputObjectTypeResolver = $this->instanceManager->getInstance(RootTaxonomyTermsFilterInputObjectTypeResolver::class);
            $this->rootTaxonomyTermsFilterInputObjectTypeResolver = $rootTaxonomyTermsFilterInputObjectTypeResolver;
        }
        return $this->rootTaxonomyTermsFilterInputObjectTypeResolver;
    }
    final protected function getQueryableTaxonomyTermTypeAPI(): QueryableTaxonomyTermTypeAPIInterface
    {
        if ($this->queryableTaxonomyTermTypeAPI === null) {
            /** @var QueryableTaxonomyTermTypeAPIInterface */
            $queryableTaxonomyTermTypeAPI = $this->instanceManager->getInstance(QueryableTaxonomyTermTypeAPIInterface::class);
            $this->queryableTaxonomyTermTypeAPI = $queryableTaxonomyTermTypeAPI;
        }
        return $this->queryableTaxonomyTermTypeAPI;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'taxonomyTerm',
            'taxonomyTerms',
            'taxonomyTermCount',
            'taxonomyTermNames',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'taxonomyTerm',
            'taxonomyTerms'
                => $this->getTaxonomyTermUnionTypeResolver(),
            'taxonomyTermCount'
                => $this->getIntScalarTypeResolver(),
            'taxonomyTermNames'
                => $this->getStringScalarTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'taxonomyTermCount'
                => SchemaTypeModifiers::NON_NULLABLE,
            'taxonomyTerms',
            'taxonomyTermNames'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'taxonomyTerm' => $this->__('Retrieve a single taxonomy term', 'taxonomyTerms'),
            'taxonomyTerms' => $this->__(' taxonomy terms', 'taxonomyTerms'),
            'taxonomyTermCount' => $this->__('Number of taxonomy terms', 'taxonomyTerms'),
            'taxonomyTermNames' => $this->__('Names of the taxonomy terms', 'taxonomyTerms'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $fieldArgNameTypeResolvers = parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        $commonFieldArgNameTypeResolvers = [
            'taxonomy' => $this->getTaxonomyTermTaxonomyEnumStringScalarTypeResolver(),
        ];
        return match ($fieldName) {
            'taxonomyTerm' => array_merge(
                $fieldArgNameTypeResolvers,
                $commonFieldArgNameTypeResolvers,
                [
                    'by' => $this->getTaxonomyTermByOneofInputObjectTypeResolver(),
                ]
            ),
            'taxonomyTerms',
            'taxonomyTermNames' => array_merge(
                $fieldArgNameTypeResolvers,
                $commonFieldArgNameTypeResolvers,
                [
                    'filter' => $this->getRootTaxonomyTermsFilterInputObjectTypeResolver(),
                    'pagination' => $this->getTaxonomyTermPaginationInputObjectTypeResolver(),
                    'sort' => $this->getTaxonomySortInputObjectTypeResolver(),
                ]
            ),
            'taxonomyTermCount' => array_merge(
                $fieldArgNameTypeResolvers,
                $commonFieldArgNameTypeResolvers,
                [
                    'filter' => $this->getRootTaxonomyTermsFilterInputObjectTypeResolver(),
                ]
            ),
            default => $fieldArgNameTypeResolvers,
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['taxonomyTerm' => 'by'] => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        if ($fieldArgName === 'taxonomy') {
            return $this->__('Taxonomy of the taxonomy term', 'taxonomyTerms');
        }
        return match ([$fieldName => $fieldArgName]) {
            ['taxonomyTerm' => 'by'] => $this->__('Parameter by which to select the taxonomy term', 'taxonomyTerms'),
            default => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    /**
     * @return array<string,mixed>
     */
    protected function getQuery(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
    ): array {
        $query = [];

        /** @var string|null */
        $taxonomyTermTaxonomy = $fieldDataAccessor->getValue('taxonomy');
        if ($taxonomyTermTaxonomy !== null) {
            $query['taxonomy'] = $taxonomyTermTaxonomy;
        }

        return $query;
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $query = array_merge(
            $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldDataAccessor),
            $this->getQuery($objectTypeResolver, $object, $fieldDataAccessor)
        );
        switch ($fieldDataAccessor->getFieldName()) {
            case 'taxonomyTerm':
                if ($taxonomyTerms = $this->getQueryableTaxonomyTermTypeAPI()->getTaxonomyTerms($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $taxonomyTerms[0];
                }
                return null;
            case 'taxonomyTerms':
                return $this->getQueryableTaxonomyTermTypeAPI()->getTaxonomyTerms($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'taxonomyTermNames':
                return $this->getQueryableTaxonomyTermTypeAPI()->getTaxonomyTerms($query, [QueryOptions::RETURN_TYPE => ReturnTypes::NAMES]);
            case 'taxonomyTermCount':
                return $this->getQueryableTaxonomyTermTypeAPI()->getTaxonomyTermCount($query);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }

    /**
     * Since the return type is known for all the fields in this
     * FieldResolver, there's no need to validate them
     */
    public function validateResolvedFieldType(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): bool {
        return false;
    }
}
