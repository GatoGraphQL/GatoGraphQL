<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\FieldResolvers\ObjectType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPCMSSchema\Tags\ModuleContracts\TagAPIRequestedContractObjectTypeFieldResolverInterface;
use PoPCMSSchema\Tags\TypeResolvers\InputObjectType\TagPaginationInputObjectTypeResolver;
use PoPCMSSchema\Tags\TypeResolvers\InputObjectType\CustomPostTagsFilterInputObjectTypeResolver;
use PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\TaxonomySortInputObjectTypeResolver;

abstract class AbstractCustomPostQueryableObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver implements TagAPIRequestedContractObjectTypeFieldResolverInterface
{
    use WithLimitFieldArgResolverTrait;

    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?TagPaginationInputObjectTypeResolver $tagPaginationInputObjectTypeResolver = null;
    private ?TaxonomySortInputObjectTypeResolver $taxonomySortInputObjectTypeResolver = null;
    private ?CustomPostTagsFilterInputObjectTypeResolver $customPostTagsFilterInputObjectTypeResolver = null;

    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        return $this->intScalarTypeResolver ??= $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setTagPaginationInputObjectTypeResolver(TagPaginationInputObjectTypeResolver $tagPaginationInputObjectTypeResolver): void
    {
        $this->tagPaginationInputObjectTypeResolver = $tagPaginationInputObjectTypeResolver;
    }
    final protected function getTagPaginationInputObjectTypeResolver(): TagPaginationInputObjectTypeResolver
    {
        return $this->tagPaginationInputObjectTypeResolver ??= $this->instanceManager->getInstance(TagPaginationInputObjectTypeResolver::class);
    }
    final public function setTaxonomySortInputObjectTypeResolver(TaxonomySortInputObjectTypeResolver $taxonomySortInputObjectTypeResolver): void
    {
        $this->taxonomySortInputObjectTypeResolver = $taxonomySortInputObjectTypeResolver;
    }
    final protected function getTaxonomySortInputObjectTypeResolver(): TaxonomySortInputObjectTypeResolver
    {
        return $this->taxonomySortInputObjectTypeResolver ??= $this->instanceManager->getInstance(TaxonomySortInputObjectTypeResolver::class);
    }
    final public function setCustomPostTagsFilterInputObjectTypeResolver(CustomPostTagsFilterInputObjectTypeResolver $customPostTagsFilterInputObjectTypeResolver): void
    {
        $this->customPostTagsFilterInputObjectTypeResolver = $customPostTagsFilterInputObjectTypeResolver;
    }
    final protected function getCustomPostTagsFilterInputObjectTypeResolver(): CustomPostTagsFilterInputObjectTypeResolver
    {
        return $this->customPostTagsFilterInputObjectTypeResolver ??= $this->instanceManager->getInstance(CustomPostTagsFilterInputObjectTypeResolver::class);
    }

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

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed> $variables
     * @param array<string, mixed> $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs,
        array $variables,
        array $expressions,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        array $options = []
    ): mixed {
        $tagTypeAPI = $this->getTagTypeAPI();
        $customPost = $object;
        $query = $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldName, $fieldArgs);
        switch ($fieldName) {
            case 'tags':
                return $tagTypeAPI->getCustomPostTags($objectTypeResolver->getID($customPost), $query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'tagNames':
                return $tagTypeAPI->getCustomPostTags($objectTypeResolver->getID($customPost), $query, [QueryOptions::RETURN_TYPE => ReturnTypes::NAMES]);
            case 'tagCount':
                return $tagTypeAPI->getCustomPostTagCount($objectTypeResolver->getID($customPost), $query);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options);
    }
}
