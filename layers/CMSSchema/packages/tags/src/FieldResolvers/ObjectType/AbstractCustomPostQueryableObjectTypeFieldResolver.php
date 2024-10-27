<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\FieldResolvers\ObjectType;

use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPCMSSchema\Tags\ModuleContracts\TagAPIRequestedContractObjectTypeFieldResolverInterface;
use PoPCMSSchema\Tags\TypeResolvers\InputObjectType\CustomPostTagsFilterInputObjectTypeResolver;
use PoPCMSSchema\Tags\TypeResolvers\InputObjectType\TagPaginationInputObjectTypeResolver;
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
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

abstract class AbstractCustomPostQueryableObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver implements TagAPIRequestedContractObjectTypeFieldResolverInterface
{
    use WithLimitFieldArgResolverTrait;

    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?TagPaginationInputObjectTypeResolver $tagPaginationInputObjectTypeResolver = null;
    private ?TaxonomySortInputObjectTypeResolver $taxonomySortInputObjectTypeResolver = null;
    private ?CustomPostTagsFilterInputObjectTypeResolver $customPostTagsFilterInputObjectTypeResolver = null;

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
    final protected function getCustomPostTagsFilterInputObjectTypeResolver(): CustomPostTagsFilterInputObjectTypeResolver
    {
        if ($this->customPostTagsFilterInputObjectTypeResolver === null) {
            /** @var CustomPostTagsFilterInputObjectTypeResolver */
            $customPostTagsFilterInputObjectTypeResolver = $this->instanceManager->getInstance(CustomPostTagsFilterInputObjectTypeResolver::class);
            $this->customPostTagsFilterInputObjectTypeResolver = $customPostTagsFilterInputObjectTypeResolver;
        }
        return $this->customPostTagsFilterInputObjectTypeResolver;
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'tags',
            'tagCount',
            'tagNames',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'tags' => $this->getTagTypeResolver(),
            'tagCount' => $this->getIntScalarTypeResolver(),
            'tagNames' => $this->getStringScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
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
            'tags' => $this->__('Tags added to this custom post', 'pop-tags'),
            'tagCount' => $this->__('Number of tags added to this custom post', 'pop-tags'),
            'tagNames' => $this->__('Names of the tags added to this custom post', 'pop-tags'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $fieldArgNameTypeResolvers = parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        return match ($fieldName) {
            'tags',
            'tagNames' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getCustomPostTagsFilterInputObjectTypeResolver(),
                    'pagination' => $this->getTagPaginationInputObjectTypeResolver(),
                    'sort' => $this->getTaxonomySortInputObjectTypeResolver(),
                ]
            ),
            'tagCount' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getCustomPostTagsFilterInputObjectTypeResolver(),
                ]
            ),
            default => $fieldArgNameTypeResolvers,
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $customPost = $object;
        $query = $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldDataAccessor);
        switch ($fieldDataAccessor->getFieldName()) {
            case 'tags':
                return $this->getTagTypeAPI()->getCustomPostTags($customPost, $query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'tagNames':
                return $this->getTagTypeAPI()->getCustomPostTags($customPost, $query, [QueryOptions::RETURN_TYPE => ReturnTypes::NAMES]);
            case 'tagCount':
                /** @var int */
                return $this->getTagTypeAPI()->getCustomPostTagCount($customPost, $query);
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
