<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\FieldInterfaceResolvers;

use PoPSchema\CustomPosts\Types\Status;
use PoP\ComponentModel\Schema\SchemaHelpers;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoPSchema\CustomPosts\Enums\CustomPostStatusEnum;
use PoPSchema\CustomPosts\Enums\CustomPostContentFormatEnum;
use PoP\ComponentModel\FieldInterfaceResolvers\EnumTypeFieldInterfaceSchemaDefinitionResolverTrait;
use PoPSchema\QueriedObject\FieldInterfaceResolvers\QueryableFieldInterfaceResolver;

class IsCustomPostFieldInterfaceResolver extends QueryableFieldInterfaceResolver
{
    use EnumTypeFieldInterfaceSchemaDefinitionResolverTrait;

    public function getInterfaceName(): string
    {
        return 'IsCustomPost';
    }

    public function getImplementedFieldInterfaceResolverClasses(): array
    {
        return [
            QueryableFieldInterfaceResolver::class,
        ];
    }

    public function getSchemaInterfaceDescription(): ?string
    {
        return $this->translationAPI->__('Entities representing a custom post', 'customposts');
    }

    public function getFieldNamesToImplement(): array
    {
        return array_merge(
            parent::getFieldNamesToImplement(),
            [
                'content',
                'status',
                'isStatus',
                'date',
                'datetime',
                'title',
                'excerpt',
                'customPostType',
            ]
        );
    }

    public function getSchemaFieldType(string $fieldName): string
    {
        $types = [
            'content' => SchemaDefinition::TYPE_STRING,
            'status' => SchemaDefinition::TYPE_ENUM,
            'isStatus' => SchemaDefinition::TYPE_BOOL,
            'date' => SchemaDefinition::TYPE_DATE,
            'datetime' => SchemaDefinition::TYPE_DATE,
            'title' => SchemaDefinition::TYPE_STRING,
            'excerpt' => SchemaDefinition::TYPE_STRING,
            'customPostType' => SchemaDefinition::TYPE_STRING,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($fieldName);
    }

    public function isSchemaFieldResponseNonNullable(string $fieldName): bool
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
            case 'datetime':
            case 'customPostType':
                return true;
        }
        return parent::isSchemaFieldResponseNonNullable($fieldName);
    }

    public function getSchemaFieldDescription(string $fieldName): ?string
    {
        $descriptions = [
            'content' => $this->translationAPI->__('Custom post content', 'customposts'),
            'status' => $this->translationAPI->__('Custom post status', 'customposts'),
            'isStatus' => $this->translationAPI->__('Is the custom post in the given status?', 'customposts'),
            'date' => $this->translationAPI->__('Custom post published date', 'customposts'),
            'datetime' => $this->translationAPI->__('Custom post published date and time', 'customposts'),
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
            case 'date':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'format',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => sprintf(
                                $this->translationAPI->__('Date format, as defined in %s', 'customposts'),
                                'https://www.php.net/manual/en/function.date.php'
                            ),
                            SchemaDefinition::ARGNAME_DEFAULT_VALUE => $this->cmsService->getOption($this->nameResolver->getName('popcms:option:dateFormat')),
                        ],
                    ]
                );

            case 'datetime':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'format',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => sprintf(
                                $this->translationAPI->__('Date and time format, as defined in %s. Default value: \'%s\' (for current year date) or \'%s\' (otherwise)', 'customposts'),
                                'https://www.php.net/manual/en/function.date.php',
                                'j M, H:i',
                                'j M Y, H:i'
                            ),
                        ],
                    ]
                );

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
                            SchemaDefinition::ARGNAME_ENUM_NAME => $customPostStatusEnum->getName(),
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
                            SchemaDefinition::ARGNAME_ENUM_NAME => $customPostContentFormatEnum->getName(),
                            SchemaDefinition::ARGNAME_ENUM_VALUES => SchemaHelpers::convertToSchemaFieldArgEnumValueDefinitions(
                                $customPostContentFormatEnum->getValues()
                            ),
                            SchemaDefinition::ARGNAME_DEFAULT_VALUE => $this->getDefaultContentFormatValue(),
                        ],
                    ]
                );
        }

        return $schemaFieldArgs;
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
                return $customPostStatusEnum->getName();
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
                    $customPostStatusEnum->getValues(),
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
