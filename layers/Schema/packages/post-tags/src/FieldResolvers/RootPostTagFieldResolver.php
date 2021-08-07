<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoPSchema\PostTags\Facades\PostTagTypeAPIFacade;
use PoPSchema\PostTags\ModuleProcessors\PostTagFieldDataloadModuleProcessor;
use PoPSchema\PostTags\TypeResolvers\PostTagTypeResolver;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\Tags\ComponentConfiguration;

class RootPostTagFieldResolver extends AbstractQueryableFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(RootTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'postTag',
            'postTagBySlug',
            'postTags',
            'postTagCount',
            'postTagNames',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'postTag' => SchemaDefinition::TYPE_ID,
            'postTagBySlug' => SchemaDefinition::TYPE_ID,
            'postTags' => SchemaDefinition::TYPE_ID,
            'postTagCount' => SchemaDefinition::TYPE_INT,
            'postTagNames' => SchemaDefinition::TYPE_STRING,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'postTagCount'
                => SchemaTypeModifiers::NON_NULLABLE,
            'postTags',
            'postTagNames'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'postTag' => $this->translationAPI->__('Post tag with a specific ID', 'pop-post-tags'),
            'postTagBySlug' => $this->translationAPI->__('Post tag with a specific slug', 'pop-post-tags'),
            'postTags' => $this->translationAPI->__('Post tags', 'pop-post-tags'),
            'postTagCount' => $this->translationAPI->__('Number of post tags', 'pop-post-tags'),
            'postTagNames' => $this->translationAPI->__('Names of the post tags', 'pop-post-tags'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
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
            case 'postTagBySlug':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'slug',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The tag slug', 'pop-post-tags'),
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
            case 'postTagBySlug':
                $query = [];
                if ($fieldName == 'postTag') {
                    $query['include'] = [$fieldArgs['id']];
                } elseif ($fieldName == 'postTagBySlug') {
                    $query['slugs'] = [$fieldArgs['slug']];
                }
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
            case 'postTagBySlug':
            case 'postTags':
                return PostTagTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
