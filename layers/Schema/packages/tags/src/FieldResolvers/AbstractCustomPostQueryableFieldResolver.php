<?php

declare(strict_types=1);

namespace PoPSchema\Tags\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoPSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPSchema\Tags\ComponentConfiguration;
use PoPSchema\Tags\ComponentContracts\TagAPIRequestedContractTrait;

abstract class AbstractCustomPostQueryableFieldResolver extends AbstractQueryableFieldResolver
{
    use TagAPIRequestedContractTrait;
    use WithLimitFieldArgResolverTrait;

    public function getFieldNamesToResolve(): array
    {
        return [
            'tags',
            'tagCount',
            'tagNames',
        ];
    }

    public function getSchemaFieldType(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): string
    {
        $types = [
            'tagCount' => SchemaDefinition::TYPE_INT,
            'tagNames' => SchemaDefinition::TYPE_STRING,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'tagCount'
                => SchemaTypeModifiers::NON_NULLABLE,
            'tags',
            'tagNames'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($relationalTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'tags' => $this->translationAPI->__('Tags added to this custom post', 'pop-tags'),
            'tagCount' => $this->translationAPI->__('Number of tags added to this custom post', 'pop-tags'),
            'tagNames' => $this->translationAPI->__('Names of the tags added to this custom post', 'pop-tags'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($relationalTypeResolver, $fieldName);
    }

    protected function getFieldDataFilteringDefaultValues(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'tags':
            case 'tagNames':
                $limitFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_LIMIT
                ]);
                return [
                    $limitFilterInputName => ComponentConfiguration::getTagListDefaultLimit(),
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
            case 'tags':
            case 'tagNames':
                if (
                    $maybeError = $this->maybeValidateLimitFieldArgument(
                        ComponentConfiguration::getTagListMaxLimit(),
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
        $tagTypeAPI = $this->getTagTypeAPI();
        $customPost = $resultItem;
        $query = $this->convertFieldArgsToFilteringQueryArgs($relationalTypeResolver, $fieldName, $fieldArgs);
        switch ($fieldName) {
            case 'tags':
                return $tagTypeAPI->getCustomPostTags($relationalTypeResolver->getID($customPost), $query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'tagNames':
                return $tagTypeAPI->getCustomPostTags($relationalTypeResolver->getID($customPost), $query, [QueryOptions::RETURN_TYPE => ReturnTypes::NAMES]);
            case 'tagCount':
                return $tagTypeAPI->getCustomPostTagCount($relationalTypeResolver->getID($customPost), $query);
        }

        return parent::resolveValue($relationalTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function getFieldTypeResolverClass(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'tags':
                return $this->getTagTypeResolverClass();
        }

        return parent::getFieldTypeResolverClass($relationalTypeResolver, $fieldName);
    }
}
