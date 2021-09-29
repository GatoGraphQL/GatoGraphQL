<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\FieldResolvers\InterfaceType;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\FieldResolvers\InterfaceType\AbstractQueryableSchemaInterfaceTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaHelpers;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\CustomPosts\Enums\CustomPostContentFormatEnum;
use PoPSchema\CustomPosts\TypeResolvers\EnumType\CustomPostContentFormatEnumTypeResolver;
use PoPSchema\CustomPosts\TypeResolvers\EnumType\CustomPostStatusEnumTypeResolver;
use PoPSchema\CustomPosts\TypeResolvers\InterfaceType\IsCustomPostInterfaceTypeResolver;
use PoPSchema\CustomPosts\Types\Status;
use PoPSchema\QueriedObject\FieldResolvers\InterfaceType\QueryableInterfaceTypeFieldResolver;
use PoPSchema\SchemaCommons\ModuleProcessors\CommonFilterInputContainerModuleProcessor;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateScalarTypeResolver;

class IsCustomPostInterfaceTypeFieldResolver extends AbstractQueryableSchemaInterfaceTypeFieldResolver
{
    protected CustomPostStatusEnumTypeResolver $customPostStatusEnumTypeResolver;
    protected CustomPostContentFormatEnumTypeResolver $customPostContentFormatEnumTypeResolver;
    protected BooleanScalarTypeResolver $booleanScalarTypeResolver;
    protected DateScalarTypeResolver $dateScalarTypeResolver;
    protected StringScalarTypeResolver $stringScalarTypeResolver;
    protected QueryableInterfaceTypeFieldResolver $queryableInterfaceTypeFieldResolver;

    #[Required]
    public function autowireIsCustomPostInterfaceTypeFieldResolver(
        CustomPostStatusEnumTypeResolver $customPostStatusEnumTypeResolver,
        CustomPostContentFormatEnumTypeResolver $customPostContentFormatEnumTypeResolver,
        BooleanScalarTypeResolver $booleanScalarTypeResolver,
        DateScalarTypeResolver $dateScalarTypeResolver,
        StringScalarTypeResolver $stringScalarTypeResolver,
        QueryableInterfaceTypeFieldResolver $queryableInterfaceTypeFieldResolver,
    ): void {
        $this->customPostStatusEnumTypeResolver = $customPostStatusEnumTypeResolver;
        $this->customPostContentFormatEnumTypeResolver = $customPostContentFormatEnumTypeResolver;
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
        $this->dateScalarTypeResolver = $dateScalarTypeResolver;
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        $this->queryableInterfaceTypeFieldResolver = $queryableInterfaceTypeFieldResolver;
    }

    public function getInterfaceTypeResolverClassesToAttachTo(): array
    {
        return [
            IsCustomPostInterfaceTypeResolver::class,
        ];
    }

    public function getImplementedInterfaceTypeFieldResolvers(): array
    {
        return [
            $this->queryableInterfaceTypeFieldResolver,
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

    public function getFieldTypeResolver(string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'isStatus'
                => $this->booleanScalarTypeResolver,
            'date',
            'modified'
                => $this->dateScalarTypeResolver,
            'content',
            'title',
            'excerpt',
            'customPostType'
                => $this->stringScalarTypeResolver,
            'status'
                => $this->customPostStatusEnumTypeResolver,
            default
                => parent::getFieldTypeResolver($fieldName),
        };
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
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'status',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_ENUM,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The status to check if the post has', 'customposts'),
                            SchemaDefinition::ARGNAME_ENUM_NAME => $this->customPostStatusEnumTypeResolver->getTypeName(),
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
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'format',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_ENUM,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The format of the content', 'customposts'),
                            SchemaDefinition::ARGNAME_ENUM_NAME => $this->customPostContentFormatEnumTypeResolver->getTypeName(),
                            SchemaDefinition::ARGNAME_ENUM_VALUES => SchemaHelpers::convertToSchemaFieldArgEnumValueDefinitions(
                                $this->customPostContentFormatEnumTypeResolver
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
}
