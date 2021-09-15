<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\FieldResolvers\InterfaceType;

use PoP\ComponentModel\FieldResolvers\InterfaceType\AbstractQueryableSchemaInterfaceTypeFieldResolver;
use PoP\ComponentModel\FieldResolvers\InterfaceType\WithEnumInterfaceTypeFieldSchemaDefinitionResolverTrait;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaHelpers;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoPSchema\CustomPosts\Enums\CustomPostContentFormatEnum;
use PoPSchema\CustomPosts\Enums\CustomPostStatusEnum;
use PoPSchema\CustomPosts\TypeResolvers\InterfaceType\IsCustomPostInterfaceTypeResolver;
use PoPSchema\CustomPosts\Types\Status;
use PoPSchema\QueriedObject\FieldResolvers\InterfaceType\QueryableInterfaceTypeFieldResolver;
use PoPSchema\SchemaCommons\ModuleProcessors\CommonFilterInputContainerModuleProcessor;

class IsCustomPostInterfaceTypeFieldResolver extends AbstractQueryableSchemaInterfaceTypeFieldResolver
{
    use WithEnumInterfaceTypeFieldSchemaDefinitionResolverTrait;

    public function getInterfaceTypeResolverClassesToAttachTo(): array
    {
        return [
            IsCustomPostInterfaceTypeResolver::class,
        ];
    }

    public function getImplementedInterfaceTypeFieldResolverClasses(): array
    {
        return [
            QueryableInterfaceTypeFieldResolver::class,
        ];
    }

    public function getFieldNamesToImplement(): array
    {
        return [
            'url',
            'urlPath',
            'slug',
            'content',
            'status',
            'isStatus',
            'date',
            'modified',
            'title',
            'excerpt',
            'customPostType',
        ];
    }

    public function getSchemaFieldType(string $fieldName): string
    {
        $types = [
            'content' => SchemaDefinition::TYPE_STRING,
            'status' => SchemaDefinition::TYPE_ENUM,
            'isStatus' => SchemaDefinition::TYPE_BOOL,
            'date' => SchemaDefinition::TYPE_DATE,
            'modified' => SchemaDefinition::TYPE_DATE,
            'title' => SchemaDefinition::TYPE_STRING,
            'excerpt' => SchemaDefinition::TYPE_STRING,
            'customPostType' => SchemaDefinition::TYPE_STRING,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($fieldName);
    }

    public function getSchemaFieldTypeModifiers(string $fieldName): ?int
    {
        /**
         * Please notice that the URL, slug, title and excerpt are nullable,
         * and content is not!
         */
        switch ($fieldName) {
            case 'content':
            case 'status':
            case 'isStatus':
            case 'date':
            case 'modified':
            case 'customPostType':
                return SchemaTypeModifiers::NON_NULLABLE;
        }
        return parent::getSchemaFieldTypeModifiers($fieldName);
    }

    public function getSchemaFieldDescription(string $fieldName): ?string
    {
        $descriptions = [
            'url' => $this->translationAPI->__('Custom post URL', 'customposts'),
            'urlPath' => $this->translationAPI->__('Custom post URL path', 'customposts'),
            'slug' => $this->translationAPI->__('Custom post slug', 'customposts'),
            'content' => $this->translationAPI->__('Custom post content', 'customposts'),
            'status' => $this->translationAPI->__('Custom post status', 'customposts'),
            'isStatus' => $this->translationAPI->__('Is the custom post in the given status?', 'customposts'),
            'date' => $this->translationAPI->__('Custom post published date', 'customposts'),
            'modified' => $this->translationAPI->__('Custom post modified date', 'customposts'),
            'title' => $this->translationAPI->__('Custom post title', 'customposts'),
            'excerpt' => $this->translationAPI->__('Custom post excerpt', 'customposts'),
            'customPostType' => $this->translationAPI->__('Custom post type', 'customposts'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($fieldName);
    }
    public function getSchemaFieldArgs(string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($fieldName);
        switch ($fieldName) {
            case 'isStatus':
                /**
                 * @var CustomPostStatusEnum
                 */
                $customPostStatusEnum = $this->instanceManager->getInstance(CustomPostStatusEnum::class);
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'status',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_ENUM,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The status to check if the post has', 'customposts'),
                            SchemaDefinition::ARGNAME_ENUM_NAME => $customPostStatusEnum->getTypeName(),
                            SchemaDefinition::ARGNAME_ENUM_VALUES => [
                                Status::PUBLISHED => [
                                    SchemaDefinition::ARGNAME_NAME => Status::PUBLISHED,
                                ],
                                Status::PENDING => [
                                    SchemaDefinition::ARGNAME_NAME => Status::PENDING,
                                ],
                                Status::DRAFT => [
                                    SchemaDefinition::ARGNAME_NAME => Status::DRAFT,
                                ],
                                Status::TRASH => [
                                    SchemaDefinition::ARGNAME_NAME => Status::TRASH,
                                ],
                                /**
                                 * @todo Extract to documentation before deleting this code
                                 */
                                // 'trashed' => [
                                //     SchemaDefinition::ARGNAME_NAME => 'trashed',
                                //     SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('Published content', 'customposts'),
                                //     SchemaDefinition::ARGNAME_DEPRECATED => true,
                                //     SchemaDefinition::ARGNAME_DEPRECATIONDESCRIPTION => sprintf(
                                //         $this->translationAPI->__('Use \'%s\' instead', 'customposts'),
                                //         Status::TRASH
                                //     ),
                                // ],
                            ],
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );

            case 'content':
                /**
                 * @var CustomPostContentFormatEnum
                 */
                $customPostContentFormatEnum = $this->instanceManager->getInstance(CustomPostContentFormatEnum::class);
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'format',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_ENUM,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The format of the content', 'customposts'),
                            SchemaDefinition::ARGNAME_ENUM_NAME => $customPostContentFormatEnum->getTypeName(),
                            SchemaDefinition::ARGNAME_ENUM_VALUES => SchemaHelpers::convertToSchemaFieldArgEnumValueDefinitions(
                                $customPostContentFormatEnum
                            ),
                            SchemaDefinition::ARGNAME_DEFAULT_VALUE => $this->getDefaultContentFormatValue(),
                        ],
                    ]
                );
        }

        return $schemaFieldArgs;
    }

    public function getFieldFilterInputContainerModule(string $fieldName): ?array
    {
        return match ($fieldName) {
            'date' => [CommonFilterInputContainerModuleProcessor::class, CommonFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_GMTDATE_AS_STRING],
            'modified' => [CommonFilterInputContainerModuleProcessor::class, CommonFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_GMTDATE_AS_STRING],
            default => parent::getFieldFilterInputContainerModule($fieldName),
        };
    }

    public function getDefaultContentFormatValue(): string
    {
        return CustomPostContentFormatEnum::HTML;
    }

    protected function getSchemaDefinitionEnumName(string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'status':
                /**
                 * @var CustomPostStatusEnum
                 */
                $customPostStatusEnum = $this->instanceManager->getInstance(CustomPostStatusEnum::class);
                return $customPostStatusEnum->getTypeName();
        }
        return null;
    }

    protected function getSchemaDefinitionEnumValues(string $fieldName): ?array
    {
        switch ($fieldName) {
            case 'status':
                /**
                 * @var CustomPostStatusEnum
                 */
                $customPostStatusEnum = $this->instanceManager->getInstance(CustomPostStatusEnum::class);
                return array_merge(
                    $customPostStatusEnum->getEnumValues(),
                    [
                        /**
                         * @todo Extract to documentation before deleting this code
                         */
                        // 'trashed',
                    ]
                );
        }
        return null;
    }

    /**
     * @todo Extract to documentation before deleting this code
     */
    // protected function getSchemaDefinitionEnumValueDeprecationDescriptions(string $fieldName): ?array
    // {
    //     switch ($fieldName) {
    //         case 'status':
    //             return [
    //                 'trashed' => sprintf(
    //                     $this->translationAPI->__('Using \'%s\' instead', 'customposts'),
    //                     Status::TRASH
    //                 ),
    //             ];
    //     }
    //     return null;
    // }

    protected function getSchemaDefinitionEnumValueDescriptions(string $fieldName): ?array
    {
        switch ($fieldName) {
            case 'status':
                return [
                    Status::PUBLISHED => $this->translationAPI->__('Published content', 'customposts'),
                    Status::PENDING => $this->translationAPI->__('Pending content', 'customposts'),
                    Status::DRAFT => $this->translationAPI->__('Draft content', 'customposts'),
                    Status::TRASH => $this->translationAPI->__('Trashed content', 'customposts'),
                    /**
                     * @todo Extract to documentation before deleting this code
                     */
                    // 'trashed' => $this->translationAPI->__('Trashed content', 'customposts'),
                ];
        }
        return null;
    }
}
