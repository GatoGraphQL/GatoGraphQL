<?php

declare(strict_types=1);

namespace PoPSchema\GenericCustomPosts\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\GenericCustomPosts\ComponentConfiguration;
use PoPSchema\GenericCustomPosts\ModuleProcessors\CommonCustomPostFilterInputContainerModuleProcessor;
use PoPSchema\GenericCustomPosts\ModuleProcessors\GenericCustomPostFilterInputContainerModuleProcessor;
use PoPSchema\GenericCustomPosts\TypeResolvers\GenericCustomPostTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoPSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;

/**
 * Add fields to the Root for querying for generic custom posts
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
class RootGenericCustomPostFieldResolver extends AbstractQueryableFieldResolver
{
    use WithLimitFieldArgResolverTrait;

    public function getClassesToAttachTo(): array
    {
        return array(RootTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'genericCustomPost',
            'genericCustomPostBySlug',
            'genericCustomPosts',
            'genericCustomPostCount',
            'genericCustomPostForAdmin',
            'genericCustomPostBySlugForAdmin',
            'genericCustomPostsForAdmin',
            'genericCustomPostCountForAdmin',
        ];
    }

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'genericCustomPost' => $this->translationAPI->__('Custom post with a specific ID', 'generic-customposts'),
            'genericCustomPostBySlug' => $this->translationAPI->__('Custom post with a specific slug', 'generic-customposts'),
            'genericCustomPosts' => $this->translationAPI->__('Custom posts', 'generic-customposts'),
            'genericCustomPostCount' => $this->translationAPI->__('Number of custom posts', 'generic-customposts'),
            'genericCustomPostForAdmin' => $this->translationAPI->__('[Unrestricted] Custom post with a specific ID', 'generic-customposts'),
            'genericCustomPostBySlugForAdmin' => $this->translationAPI->__('[Unrestricted] Custom post with a specific slug', 'generic-customposts'),
            'genericCustomPostsForAdmin' => $this->translationAPI->__('[Unrestricted] Custom posts', 'generic-customposts'),
            'genericCustomPostCountForAdmin' => $this->translationAPI->__('[Unrestricted] Number of custom posts', 'generic-customposts'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldType(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): string
    {
        $types = [
            'genericCustomPost' => SchemaDefinition::TYPE_ID,
            'genericCustomPostBySlug' => SchemaDefinition::TYPE_ID,
            'genericCustomPosts' => SchemaDefinition::TYPE_ID,
            'genericCustomPostCount' => SchemaDefinition::TYPE_INT,
            'genericCustomPostForAdmin' => SchemaDefinition::TYPE_ID,
            'genericCustomPostBySlugForAdmin' => SchemaDefinition::TYPE_ID,
            'genericCustomPostsForAdmin' => SchemaDefinition::TYPE_ID,
            'genericCustomPostCountForAdmin' => SchemaDefinition::TYPE_INT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'genericCustomPostCount',
            'genericCustomPostCountForAdmin'
                => SchemaTypeModifiers::NON_NULLABLE,
            'genericCustomPosts',
            'genericCustomPostsForAdmin'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default => parent::getSchemaFieldTypeModifiers($relationalTypeResolver, $fieldName),
        };
    }

    public function getFieldDataFilteringModule(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'genericCustomPosts' => [
                GenericCustomPostFilterInputContainerModuleProcessor::class,
                GenericCustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_GENERICCUSTOMPOSTLIST
            ],
            'genericCustomPostCount' => [
                GenericCustomPostFilterInputContainerModuleProcessor::class,
                GenericCustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_GENERICCUSTOMPOSTCOUNT
            ],
            'genericCustomPostsForAdmin' => [
                GenericCustomPostFilterInputContainerModuleProcessor::class,
                GenericCustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINGENERICCUSTOMPOSTLIST
            ],
            'genericCustomPostCountForAdmin' => [
                GenericCustomPostFilterInputContainerModuleProcessor::class,
                GenericCustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINGENERICCUSTOMPOSTCOUNT
            ],
            'genericCustomPost' => [
                CommonCustomPostFilterInputContainerModuleProcessor::class,
                CommonCustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_GENERICTYPE
            ],
            'genericCustomPostForAdmin' => [
                CommonCustomPostFilterInputContainerModuleProcessor::class,
                CommonCustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS_GENERICTYPE
            ],
            'genericCustomPostBySlug' => [
                CommonCustomPostFilterInputContainerModuleProcessor::class,
                CommonCustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_GENERICTYPE
            ],
            'genericCustomPostBySlugForAdmin' => [
                CommonCustomPostFilterInputContainerModuleProcessor::class,
                CommonCustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS_GENERICTYPE
            ],
            default => parent::getFieldDataFilteringModule($relationalTypeResolver, $fieldName),
        };
    }

    protected function getFieldDataFilteringDefaultValues(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'genericCustomPosts':
            case 'genericCustomPostsForAdmin':
                $limitFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_LIMIT
                ]);
                return [
                    $limitFilterInputName => ComponentConfiguration::getGenericCustomPostListDefaultLimit(),
                ];
        }
        return parent::getFieldDataFilteringDefaultValues($relationalTypeResolver, $fieldName);
    }

    /**
     * Validate the constraints for a field argument
     *
     * @return string[] Error messages
     */
    public function validateFieldArgument(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array {
        $errors = parent::validateFieldArgument(
            $relationalTypeResolver,
            $fieldName,
            $fieldArgName,
            $fieldArgValue,
        );

        // Check the "limit" fieldArg
        switch ($fieldName) {
            case 'genericCustomPosts':
            case 'genericCustomPostsForAdmin':
                if (
                    $maybeError = $this->maybeValidateLimitFieldArgument(
                        ComponentConfiguration::getGenericCustomPostListMaxLimit(),
                        $fieldName,
                        $fieldArgName,
                        $fieldArgValue
                    )
                ) {
                    $errors[] = $maybeError;
                }
                break;
        }
        return $errors;
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @return array<string, mixed>
     */
    protected function getQuery(
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = []
    ): array {
        switch ($fieldName) {
            case 'genericCustomPost':
            case 'genericCustomPostForAdmin':
            case 'genericCustomPostBySlug':
            case 'genericCustomPostBySlugForAdmin':
            case 'genericCustomPosts':
            case 'genericCustomPostsForAdmin':
            case 'genericCustomPostCount':
            case 'genericCustomPostCountForAdmin':
                return [
                    'custompost-types' => ComponentConfiguration::getGenericCustomPostTypes(),
                ];
        }
        return [];
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
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $query = array_merge(
            $this->convertFieldArgsToFilteringQueryArgs($relationalTypeResolver, $fieldName, $fieldArgs),
            $this->getQuery($relationalTypeResolver, $resultItem, $fieldName, $fieldArgs)
        );
        switch ($fieldName) {
            case 'genericCustomPost':
            case 'genericCustomPostBySlug':
            case 'genericCustomPostForAdmin':
            case 'genericCustomPostBySlugForAdmin':
                if ($customPosts = $customPostTypeAPI->getCustomPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $customPosts[0];
                }
                return null;
            case 'genericCustomPosts':
            case 'genericCustomPostsForAdmin':
                return $customPostTypeAPI->getCustomPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'genericCustomPostCount':
            case 'genericCustomPostCountForAdmin':
                return $customPostTypeAPI->getCustomPostCount($query);
        }

        return parent::resolveValue($relationalTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'genericCustomPost':
            case 'genericCustomPostBySlug':
            case 'genericCustomPosts':
            case 'genericCustomPostForAdmin':
            case 'genericCustomPostBySlugForAdmin':
            case 'genericCustomPostsForAdmin':
                return GenericCustomPostTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($relationalTypeResolver, $fieldName);
    }
}
