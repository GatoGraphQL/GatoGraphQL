<?php

declare(strict_types=1);

namespace PoPSchema\GenericCustomPosts\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\CustomPosts\Types\Status;
use PoPSchema\GenericCustomPosts\ComponentConfiguration;
use PoPSchema\GenericCustomPosts\ModuleProcessors\GenericCustomPostFilterInputContainerModuleProcessor;
use PoPSchema\GenericCustomPosts\TypeResolvers\GenericCustomPostTypeResolver;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

/**
 * Add fields to the Root for querying for generic custom posts
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
class RootGenericCustomPostFieldResolver extends AbstractQueryableFieldResolver
{
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
            'unrestrictedGenericCustomPost',
            'unrestrictedGenericCustomPostBySlug',
            'unrestrictedGenericCustomPosts',
            'unrestrictedGenericCustomPostCount',
        ];
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'genericCustomPost' => $this->translationAPI->__('Custom post with a specific ID', 'generic-customposts'),
            'genericCustomPostBySlug' => $this->translationAPI->__('Custom post with a specific slug', 'generic-customposts'),
            'genericCustomPosts' => $this->translationAPI->__('Custom posts', 'generic-customposts'),
            'genericCustomPostCount' => $this->translationAPI->__('Number of custom posts', 'generic-customposts'),
            'unrestrictedGenericCustomPost' => $this->translationAPI->__('[Unrestricted] Custom post with a specific ID', 'generic-customposts'),
            'unrestrictedGenericCustomPostBySlug' => $this->translationAPI->__('[Unrestricted] Custom post with a specific slug', 'generic-customposts'),
            'unrestrictedGenericCustomPosts' => $this->translationAPI->__('[Unrestricted] Custom posts', 'generic-customposts'),
            'unrestrictedGenericCustomPostCount' => $this->translationAPI->__('[Unrestricted] Number of custom posts', 'generic-customposts'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'genericCustomPost' => SchemaDefinition::TYPE_ID,
            'genericCustomPostBySlug' => SchemaDefinition::TYPE_ID,
            'genericCustomPosts' => SchemaDefinition::TYPE_ID,
            'genericCustomPostCount' => SchemaDefinition::TYPE_INT,
            'unrestrictedGenericCustomPost' => SchemaDefinition::TYPE_ID,
            'unrestrictedGenericCustomPostBySlug' => SchemaDefinition::TYPE_ID,
            'unrestrictedGenericCustomPosts' => SchemaDefinition::TYPE_ID,
            'unrestrictedGenericCustomPostCount' => SchemaDefinition::TYPE_INT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'genericCustomPostCount',
            'unrestrictedGenericCustomPostCount'
                => SchemaTypeModifiers::NON_NULLABLE,
            'genericCustomPosts',
            'unrestrictedGenericCustomPosts'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default => parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName),
        };
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        switch ($fieldName) {
            case 'genericCustomPost':
            case 'unrestrictedGenericCustomPost':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'id',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_ID,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The generic custom post ID', 'generic-customposts'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );
            case 'genericCustomPostBySlug':
            case 'unrestrictedGenericCustomPostBySlug':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'slug',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The generic custom post slug', 'generic-customposts'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );
            case 'genericCustomPosts':
            case 'genericCustomPostCount':
            case 'unrestrictedGenericCustomPosts':
            case 'unrestrictedGenericCustomPostCount':
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
            case 'genericCustomPosts':
            case 'genericCustomPostCount':
            case 'unrestrictedGenericCustomPosts':
            case 'unrestrictedGenericCustomPostCount':
                return false;
        }
        return parent::enableOrderedSchemaFieldArgs($typeResolver, $fieldName);
    }

    protected function getFieldDataFilteringModule(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?array
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
            'unrestrictedGenericCustomPosts' => [
                GenericCustomPostFilterInputContainerModuleProcessor::class,
                GenericCustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINGENERICCUSTOMPOSTLIST
            ],
            'unrestrictedGenericCustomPostCount' => [
                GenericCustomPostFilterInputContainerModuleProcessor::class,
                GenericCustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINGENERICCUSTOMPOSTCOUNT
            ],
            default => parent::getFieldDataFilteringModule($typeResolver, $fieldName, $fieldArgs),
        };
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
        $query = [
            'custompost-types' => ComponentConfiguration::getGenericCustomPostTypes(),
            'status' => [
                Status::PUBLISHED,
            ],
        ];
        if (
            in_array($fieldName, [
            'genericCustomPost',
            'genericCustomPostBySlug',
            'genericCustomPosts',
            'genericCustomPostCount',
            ])
        ) {
            $query['status'] = [
                Status::PUBLISHED,
            ];
        } elseif (
            in_array($fieldName, [
            'unrestrictedGenericCustomPost',
            'unrestrictedGenericCustomPostBySlug',
            'unrestrictedGenericCustomPosts',
            'unrestrictedGenericCustomPostCount',
            ])
        ) {
            $query['status'] = [
                Status::PUBLISHED,
                Status::DRAFT,
                Status::PENDING,
                Status::TRASH,
            ];
        }
        switch ($fieldName) {
            case 'genericCustomPost':
            case 'unrestrictedGenericCustomPost':
                return array_merge(
                    $query,
                    [
                        'include' => [$fieldArgs['id']],
                    ]
                );
            case 'genericCustomPostBySlug':
            case 'unrestrictedGenericCustomPostBySlug':
                return array_merge(
                    $query,
                    [
                        'slug' => $fieldArgs['slug'],
                    ]
                );
            case 'genericCustomPosts':
            case 'unrestrictedGenericCustomPosts':
                return array_merge(
                    $query,
                    [
                        'limit' => ComponentConfiguration::getGenericCustomPostListDefaultLimit(),
                    ]
                );
            case 'genericCustomPostCount':
            case 'unrestrictedGenericCustomPostCount':
                return $query;
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
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        switch ($fieldName) {
            case 'genericCustomPost':
            case 'genericCustomPostBySlug':
            case 'unrestrictedGenericCustomPost':
            case 'unrestrictedGenericCustomPostBySlug':
                $query = $this->getQuery($typeResolver, $resultItem, $fieldName, $fieldArgs);
                $options = [
                    'return-type' => ReturnTypes::IDS,
                ];
                if ($customPosts = $customPostTypeAPI->getCustomPosts($query, $options)) {
                    return $customPosts[0];
                }
                return null;
            case 'genericCustomPosts':
            case 'unrestrictedGenericCustomPosts':
                $query = $this->getQuery($typeResolver, $resultItem, $fieldName, $fieldArgs);
                $options = [
                    'return-type' => ReturnTypes::IDS,
                ];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
                return $customPostTypeAPI->getCustomPosts($query, $options);
            case 'genericCustomPostCount':
            case 'unrestrictedGenericCustomPostCount':
                $query = $this->getQuery($typeResolver, $resultItem, $fieldName, $fieldArgs);
                $options = [];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
                return $customPostTypeAPI->getCustomPostCount($query, $options);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'genericCustomPost':
            case 'genericCustomPostBySlug':
            case 'genericCustomPosts':
            case 'unrestrictedGenericCustomPost':
            case 'unrestrictedGenericCustomPostBySlug':
            case 'unrestrictedGenericCustomPosts':
                return GenericCustomPostTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
