<?php

declare(strict_types=1);

namespace PoPSchema\Posts\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\Posts\ComponentConfiguration;
use PoPSchema\Posts\Facades\PostTypeAPIFacade;
use PoPSchema\Posts\ModuleProcessors\PostFilterInputContainerModuleProcessor;
use PoPSchema\Posts\TypeResolvers\PostTypeResolver;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoP\ComponentModel\FilterInput\FilterInputHelper;

abstract class AbstractPostFieldResolver extends AbstractQueryableFieldResolver
{
    public function getFieldNamesToResolve(): array
    {
        return [
            'posts',
            'postCount',
            'unrestrictedPosts',
            'unrestrictedPostCount',
        ];
    }

    public function getAdminFieldNames(): array
    {
        return [
            'unrestrictedPosts',
            'unrestrictedPostCount',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'posts' => SchemaDefinition::TYPE_ID,
            'postCount' => SchemaDefinition::TYPE_INT,
            'unrestrictedPosts' => SchemaDefinition::TYPE_ID,
            'unrestrictedPostCount' => SchemaDefinition::TYPE_INT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'postCount',
            'unrestrictedPostCount'
                => SchemaTypeModifiers::NON_NULLABLE,
            'posts',
            'unrestrictedPosts'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'posts' => $this->translationAPI->__('Posts', 'pop-posts'),
            'postCount' => $this->translationAPI->__('Number of posts', 'pop-posts'),
            'unrestrictedPosts' => $this->translationAPI->__('[Unrestricted] Posts', 'pop-posts'),
            'unrestrictedPostCount' => $this->translationAPI->__('[Unrestricted] Number of posts', 'pop-posts'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getFieldDataFilteringModule(TypeResolverInterface $typeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'posts' => [PostFilterInputContainerModuleProcessor::class, PostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_POSTS],
            'postCount' => [PostFilterInputContainerModuleProcessor::class, PostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_POSTCOUNT],
            'unrestrictedPosts' => [PostFilterInputContainerModuleProcessor::class, PostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINPOSTS],
            'unrestrictedPostCount' => [PostFilterInputContainerModuleProcessor::class, PostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINPOSTCOUNT],
            default => parent::getFieldDataFilteringModule($typeResolver, $fieldName),
        };
    }

    protected function getFieldDataFilteringDefaultValues(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'posts':
            case 'unrestrictedPosts':
                $limitFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_LIMIT
                ]);
                return [
                    $limitFilterInputName => ComponentConfiguration::getPostListDefaultLimit(),
                ];
        }
        return parent::getFieldDataFilteringDefaultValues($typeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @return array<string, mixed>
     */
    protected function getQuery(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = []
    ): array {
        return [];
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
        $postTypeAPI = PostTypeAPIFacade::getInstance();
        $query = array_merge(
            $this->convertFieldArgsToFilteringQueryArgs($typeResolver, $fieldName, $fieldArgs),
            $this->getQuery($typeResolver, $resultItem, $fieldName, $fieldArgs)
        );
        switch ($fieldName) {
            case 'posts':
            case 'unrestrictedPosts':
                return $postTypeAPI->getPosts($query, ['return-type' => ReturnTypes::IDS]);

            case 'postCount':
            case 'unrestrictedPostCount':
                return $postTypeAPI->getPostCount($query);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'posts':
            case 'unrestrictedPosts':
                return PostTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
