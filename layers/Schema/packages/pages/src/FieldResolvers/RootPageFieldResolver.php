<?php

declare(strict_types=1);

namespace PoPSchema\Pages\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoPSchema\Pages\ModuleProcessors\PageFilterInputContainerModuleProcessor;
use PoPSchema\CustomPosts\Types\Status;
use PoPSchema\Pages\ComponentConfiguration;
use PoPSchema\Pages\Facades\PageTypeAPIFacade;
use PoPSchema\Pages\TypeResolvers\PageTypeResolver;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

class RootPageFieldResolver extends AbstractQueryableFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(RootTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'page',
            'pageBySlug',
            'pages',
            'pageCount',
            'unrestrictedPage',
            'unrestrictedPageBySlug',
            'unrestrictedPages',
            'unrestrictedPageCount',
        ];
    }

    public function getAdminFieldNames(): array
    {
        return [
            'unrestrictedPage',
            'unrestrictedPageBySlug',
            'unrestrictedPages',
            'unrestrictedPageCount',
        ];
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'page' => $this->translationAPI->__('Page with a specific ID', 'pages'),
            'pageBySlug' => $this->translationAPI->__('Page with a specific slug', 'pages'),
            'pages' => $this->translationAPI->__('Pages', 'pages'),
            'pageCount' => $this->translationAPI->__('Number of pages', 'pages'),
            'unrestrictedPage' => $this->translationAPI->__('[Unrestricted] Page with a specific ID', 'pages'),
            'unrestrictedPageBySlug' => $this->translationAPI->__('[Unrestricted] Page with a specific slug', 'pages'),
            'unrestrictedPages' => $this->translationAPI->__('[Unrestricted] Pages', 'pages'),
            'unrestrictedPageCount' => $this->translationAPI->__('[Unrestricted] Number of pages', 'pages'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'page' => SchemaDefinition::TYPE_ID,
            'pageBySlug' => SchemaDefinition::TYPE_ID,
            'pages' => SchemaDefinition::TYPE_ID,
            'pageCount' => SchemaDefinition::TYPE_INT,
            'unrestrictedPage' => SchemaDefinition::TYPE_ID,
            'unrestrictedPageBySlug' => SchemaDefinition::TYPE_ID,
            'unrestrictedPages' => SchemaDefinition::TYPE_ID,
            'unrestrictedPageCount' => SchemaDefinition::TYPE_INT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'pageCount',
            'unrestrictedPageCount'
                => SchemaTypeModifiers::NON_NULLABLE,
            'pages',
            'unrestrictedPages'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName),
        };
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        switch ($fieldName) {
            case 'page':
            case 'unrestrictedPage':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'id',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_ID,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The page ID', 'pages'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );
            case 'pageBySlug':
            case 'unrestrictedPageBySlug':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'slug',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The page slug', 'pages'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );
            case 'pages':
            case 'pageCount':
            case 'unrestrictedPages':
            case 'unrestrictedPageCount':
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
            case 'pages':
            case 'pageCount':
            case 'unrestrictedPages':
            case 'unrestrictedPageCount':
                return false;
        }
        return parent::enableOrderedSchemaFieldArgs($typeResolver, $fieldName);
    }

    protected function getFieldDataFilteringModule(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?array
    {
        return match ($fieldName) {
            'pages' => [
                PageFilterInputContainerModuleProcessor::class,
                PageFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_PAGELISTLIST
            ],
            'pageCount' => [
                PageFilterInputContainerModuleProcessor::class,
                PageFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_PAGELISTCOUNT
            ],
            'unrestrictedPages' => [
                PageFilterInputContainerModuleProcessor::class,
                PageFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINPAGELISTLIST
            ],
            'unrestrictedPageCount' => [
                PageFilterInputContainerModuleProcessor::class,
                PageFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINPAGELISTCOUNT
            ],
            default => parent::getFieldDataFilteringModule($typeResolver, $fieldName, $fieldArgs),
        };
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
        $pageTypeAPI = PageTypeAPIFacade::getInstance();
        switch ($fieldName) {
            case 'page':
            case 'pageBySlug':
            case 'unrestrictedPage':
            case 'unrestrictedPageBySlug':
                $query = [];
                if (
                    in_array($fieldName, [
                    'page',
                    'unrestrictedPage',
                    ])
                ) {
                    $query['include'] = [$fieldArgs['id']];
                } elseif (
                    in_array($fieldName, [
                    'pageBySlug',
                    'unrestrictedPageBySlug',
                    ])
                ) {
                    $query['slug'] = $fieldArgs['slug'];
                }

                if (
                    in_array($fieldName, [
                    'page',
                    'pageBySlug',
                    ])
                ) {
                    $query['status'] = [
                        Status::PUBLISHED,
                    ];
                } elseif (
                    in_array($fieldName, [
                    'unrestrictedPage',
                    'unrestrictedPageBySlug',
                    ])
                ) {
                    $query['status'] = [
                        Status::PUBLISHED,
                        Status::DRAFT,
                        Status::PENDING,
                        Status::TRASH,
                    ];
                }
                $options = [
                    'return-type' => ReturnTypes::IDS,
                ];
                if ($pages = $pageTypeAPI->getPages($query, $options)) {
                    return $pages[0];
                }
                return null;
            case 'pages':
            case 'unrestrictedPages':
                $query = [
                    'limit' => ComponentConfiguration::getPageListDefaultLimit(),
                    'status' => [
                        Status::PUBLISHED,
                    ],
                ];
                $options = [
                    'return-type' => ReturnTypes::IDS,
                ];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
                return $pageTypeAPI->getPages($query, $options);
            case 'pageCount':
            case 'unrestrictedPageCount':
                $query = [
                    'status' => [
                        Status::PUBLISHED,
                    ],
                ];
                $options = [];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
                return $pageTypeAPI->getPageCount($query, $options);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'page':
            case 'pageBySlug':
            case 'pages':
            case 'unrestrictedPage':
            case 'unrestrictedPageBySlug':
            case 'unrestrictedPages':
                return PageTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
