<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\FieldResolvers;

use PoPSchema\Tags\ComponentConfiguration;
use PoPSchema\PostTags\TypeResolvers\PostTagTypeResolver;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\PostTags\ModuleProcessors\PostTagFieldDataloadModuleProcessor;
use PoPSchema\PostTags\Facades\PostTagTypeAPIFacade;

class RootPostTagFieldResolver extends AbstractQueryableFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(RootTypeResolver::class);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'postTag' => $this->translationAPI->__('Post tag with a specific ID', 'pop-post-tags'),
            'postTags' => $this->translationAPI->__('Post tags', 'pop-post-tags'),
            'postTagCount' => $this->translationAPI->__('Number of post tags', 'pop-post-tags'),
            'postTagNames' => $this->translationAPI->__('Names of the post tags', 'pop-post-tags'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'postTag',
            'postTags',
            'postTagCount',
            'postTagNames',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $types = [
            'postTag' => SchemaDefinition::TYPE_ID,
            'postTags' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            'postTagCount' => SchemaDefinition::TYPE_INT,
            'postTagNames' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_STRING),
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function isSchemaFieldResponseNonNullable(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        $nonNullableFieldNames = [
            'postTags',
            'postTagCount',
            'postTagNames',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        switch ($fieldName) {
            case 'postTag':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'id',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_ID,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The tag ID', 'pop-post-tags'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );
            case 'postTags':
            case 'postTagCount':
            case 'postTagNames':
                return array_merge(
                    $schemaFieldArgs,
                    $this->getFieldArgumentsSchemaDefinitions($typeResolver, $fieldName)
                );
        }
        return $schemaFieldArgs;
    }

    public function enableOrderedSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        switch ($fieldName) {
            case 'postTags':
            case 'postTagCount':
            case 'postTagNames':
                return false;
        }
        return parent::enableOrderedSchemaFieldArgs($typeResolver, $fieldName);
    }

    protected function getFieldDefaultFilterDataloadingModule(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?array
    {
        switch ($fieldName) {
            case 'postTagCount':
                return [PostTagFieldDataloadModuleProcessor::class, PostTagFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_TAGCOUNT];
            case 'postTagNames':
                return [PostTagFieldDataloadModuleProcessor::class, PostTagFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST];
        }
        return parent::getFieldDefaultFilterDataloadingModule($typeResolver, $fieldName, $fieldArgs);
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
        $postTagTypeAPI = PostTagTypeAPIFacade::getInstance();
        switch ($fieldName) {
            case 'postTag':
                $query = [
                    'include' => [$fieldArgs['id']],
                ];
                $options = [
                    'return-type' => ReturnTypes::IDS,
                ];
                if ($tags = $postTagTypeAPI->getTags($query, $options)) {
                    return $tags[0];
                }
                return null;
            case 'postTags':
            case 'postTagNames':
                $query = [
                    'limit' => ComponentConfiguration::getTagListDefaultLimit(),
                ];
                $options = [
                    'return-type' => $fieldName === 'postTags' ? ReturnTypes::IDS : ReturnTypes::NAMES,
                ];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
                return $postTagTypeAPI->getTags($query, $options);
            case 'postTagCount':
                $options = [];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
                return $postTagTypeAPI->getTagCount([], $options);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'postTag':
            case 'postTags':
                return PostTagTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
