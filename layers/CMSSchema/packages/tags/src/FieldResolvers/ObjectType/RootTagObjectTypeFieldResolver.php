<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\FieldResolvers\ObjectType;

use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPCMSSchema\Tags\TypeAPIs\QueryableTagTypeAPIInterface;
use PoPCMSSchema\Tags\TypeResolvers\EnumType\TagTaxonomyEnumStringScalarTypeResolver;
use PoPCMSSchema\Tags\TypeResolvers\InputObjectType\RootTagsFilterInputObjectTypeResolver;
use PoPCMSSchema\Tags\TypeResolvers\InputObjectType\TagByOneofInputObjectTypeResolver;
use PoPCMSSchema\Tags\TypeResolvers\InputObjectType\TagPaginationInputObjectTypeResolver;
use PoPCMSSchema\Tags\TypeResolvers\UnionType\TagUnionTypeResolver;
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

class RootTagObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;

    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?TagUnionTypeResolver $tagUnionTypeResolver = null;
    private ?TagByOneofInputObjectTypeResolver $tagByOneofInputObjectTypeResolver = null;
    private ?TagTaxonomyEnumStringScalarTypeResolver $tagTaxonomyEnumStringScalarTypeResolver = null;
    private ?TagPaginationInputObjectTypeResolver $tagPaginationInputObjectTypeResolver = null;
    private ?TaxonomySortInputObjectTypeResolver $taxonomySortInputObjectTypeResolver = null;
    private ?RootTagsFilterInputObjectTypeResolver $rootTagsFilterInputObjectTypeResolver = null;
    private ?QueryableTagTypeAPIInterface $queryableTagTypeAPI = null;

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
    final protected function getTagUnionTypeResolver(): TagUnionTypeResolver
    {
        if ($this->tagUnionTypeResolver === null) {
            /** @var TagUnionTypeResolver */
            $tagUnionTypeResolver = $this->instanceManager->getInstance(TagUnionTypeResolver::class);
            $this->tagUnionTypeResolver = $tagUnionTypeResolver;
        }
        return $this->tagUnionTypeResolver;
    }
    final protected function getTagByOneofInputObjectTypeResolver(): TagByOneofInputObjectTypeResolver
    {
        if ($this->tagByOneofInputObjectTypeResolver === null) {
            /** @var TagByOneofInputObjectTypeResolver */
            $tagByOneofInputObjectTypeResolver = $this->instanceManager->getInstance(TagByOneofInputObjectTypeResolver::class);
            $this->tagByOneofInputObjectTypeResolver = $tagByOneofInputObjectTypeResolver;
        }
        return $this->tagByOneofInputObjectTypeResolver;
    }
    final protected function getTagTaxonomyEnumStringScalarTypeResolver(): TagTaxonomyEnumStringScalarTypeResolver
    {
        if ($this->tagTaxonomyEnumStringScalarTypeResolver === null) {
            /** @var TagTaxonomyEnumStringScalarTypeResolver */
            $tagTaxonomyEnumStringScalarTypeResolver = $this->instanceManager->getInstance(TagTaxonomyEnumStringScalarTypeResolver::class);
            $this->tagTaxonomyEnumStringScalarTypeResolver = $tagTaxonomyEnumStringScalarTypeResolver;
        }
        return $this->tagTaxonomyEnumStringScalarTypeResolver;
    }
    final protected function getTagPaginationInputObjectTypeResolver(): TagPaginationInputObjectTypeResolver
    {
        if ($this->tagPaginationInputObjectTypeResolver === null) {
            /** @var TagPaginationInputObjectTypeResolver */
            $tagPaginationInputObjectTypeResolver = $this->instanceManager->getInstance(TagPaginationInputObjectTypeResolver::class);
            $this->tagPaginationInputObjectTypeResolver = $tagPaginationInputObjectTypeResolver;
        }
        return $this->tagPaginationInputObjectTypeResolver;
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
    final protected function getRootTagsFilterInputObjectTypeResolver(): RootTagsFilterInputObjectTypeResolver
    {
        if ($this->rootTagsFilterInputObjectTypeResolver === null) {
            /** @var RootTagsFilterInputObjectTypeResolver */
            $rootTagsFilterInputObjectTypeResolver = $this->instanceManager->getInstance(RootTagsFilterInputObjectTypeResolver::class);
            $this->rootTagsFilterInputObjectTypeResolver = $rootTagsFilterInputObjectTypeResolver;
        }
        return $this->rootTagsFilterInputObjectTypeResolver;
    }
    final protected function getQueryableTagTypeAPI(): QueryableTagTypeAPIInterface
    {
        if ($this->queryableTagTypeAPI === null) {
            /** @var QueryableTagTypeAPIInterface */
            $queryableTagTypeAPI = $this->instanceManager->getInstance(QueryableTagTypeAPIInterface::class);
            $this->queryableTagTypeAPI = $queryableTagTypeAPI;
        }
        return $this->queryableTagTypeAPI;
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
            'tag',
            'tags',
            'tagCount',
            'tagNames',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'tag',
            'tags'
                => $this->getTagUnionTypeResolver(),
            'tagCount'
                => $this->getIntScalarTypeResolver(),
            'tagNames'
                => $this->getStringScalarTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'tagCount'
                => SchemaTypeModifiers::NON_NULLABLE,
            'tags',
            'tagNames'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'tag' => $this->__('Retrieve a single tag', 'tags'),
            'tags' => $this->__(' tags', 'tags'),
            'tagCount' => $this->__('Number of tags', 'tags'),
            'tagNames' => $this->__('Names of the tags', 'tags'),
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
            'taxonomy' => $this->getTagTaxonomyEnumStringScalarTypeResolver(),
        ];
        return match ($fieldName) {
            'tag' => array_merge(
                $fieldArgNameTypeResolvers,
                $commonFieldArgNameTypeResolvers,
                [
                    'by' => $this->getTagByOneofInputObjectTypeResolver(),
                ]
            ),
            'tags',
            'tagNames' => array_merge(
                $fieldArgNameTypeResolvers,
                $commonFieldArgNameTypeResolvers,
                [
                    'filter' => $this->getRootTagsFilterInputObjectTypeResolver(),
                    'pagination' => $this->getTagPaginationInputObjectTypeResolver(),
                    'sort' => $this->getTaxonomySortInputObjectTypeResolver(),
                ]
            ),
            'tagCount' => array_merge(
                $fieldArgNameTypeResolvers,
                $commonFieldArgNameTypeResolvers,
                [
                    'filter' => $this->getRootTagsFilterInputObjectTypeResolver(),
                ]
            ),
            default => $fieldArgNameTypeResolvers,
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['tag' => 'by'] => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        if ($fieldArgName === 'taxonomy') {
            return $this->__('Taxonomy of the tag', 'tags');
        }
        return match ([$fieldName => $fieldArgName]) {
            ['tag' => 'by'] => $this->__('Parameter by which to select the tag', 'tags'),
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
        $tagTaxonomy = $fieldDataAccessor->getValue('taxonomy');
        if ($tagTaxonomy !== null) {
            $query['taxonomy'] = $tagTaxonomy;
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
            case 'tag':
                if ($tags = $this->getQueryableTagTypeAPI()->getTags($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $tags[0];
                }
                return null;
            case 'tags':
                return $this->getQueryableTagTypeAPI()->getTags($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'tagNames':
                return $this->getQueryableTagTypeAPI()->getTags($query, [QueryOptions::RETURN_TYPE => ReturnTypes::NAMES]);
            case 'tagCount':
                return $this->getQueryableTagTypeAPI()->getTagCount($query);
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
