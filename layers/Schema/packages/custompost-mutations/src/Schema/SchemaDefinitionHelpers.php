<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMutations\Schema;

use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaHelpers;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPostMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\CustomPosts\Enums\CustomPostStatusEnum;
use PoPSchema\CustomPosts\TypeResolvers\EnumType\CustomPostStatusEnumTypeResolver;

class SchemaDefinitionHelpers
{
    public const HOOK_UPDATE_SCHEMA_FIELD_ARGS = __CLASS__ . ':update-schema-field-args';

    private static array $schemaFieldArgsCache = [];

    public static function getCreateUpdateCustomPostSchemaFieldArgs(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $fieldName,
        bool $addCustomPostID,
        ?string $entityTypeResolverClass = null
    ): array {
        $key = get_class($relationalTypeResolver) . '-' . $fieldName;
        if (!isset(self::$schemaFieldArgsCache[$key])) {
            $hooksAPI = HooksAPIFacade::getInstance();
            $translationAPI = TranslationAPIFacade::getInstance();
            $instanceManager = InstanceManagerFacade::getInstance();
            /**
             * @var CustomPostStatusEnumTypeResolver
             */
            $customPostStatusEnumTypeResolver = $instanceManager->getInstance(CustomPostStatusEnumTypeResolver::class);
            $schemaFieldDefinition = array_merge(
                $addCustomPostID ? [
                    [
                        SchemaDefinition::ARGNAME_NAME => MutationInputProperties::ID,
                        SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_ID,
                        SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The ID of the custom post to update', 'custompost-mutations'),
                        SchemaDefinition::ARGNAME_MANDATORY => true,
                    ],
                ] : [],
                [
                    [
                        SchemaDefinition::ARGNAME_NAME => MutationInputProperties::TITLE,
                        SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                        SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The title of the custom post', 'custompost-mutations'),
                    ],
                    [
                        SchemaDefinition::ARGNAME_NAME => MutationInputProperties::CONTENT,
                        SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                        SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The content of the custom post', 'custompost-mutations'),
                    ],
                    [
                        SchemaDefinition::ARGNAME_NAME => MutationInputProperties::STATUS,
                        SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_ENUM,
                        SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The status of the custom post', 'custompost-mutations'),
                        SchemaDefinition::ARGNAME_ENUM_NAME => $customPostStatusEnumTypeResolver->getTypeName(),
                        SchemaDefinition::ARGNAME_ENUM_VALUES => SchemaHelpers::convertToSchemaFieldArgEnumValueDefinitions(
                            $customPostStatusEnumTypeResolver
                        ),
                    ],
                ]
            );
            self::$schemaFieldArgsCache[$key] = $hooksAPI->applyFilters(
                self::HOOK_UPDATE_SCHEMA_FIELD_ARGS,
                $schemaFieldDefinition,
                $relationalTypeResolver,
                $fieldName,
                $entityTypeResolverClass
            );
        }
        return self::$schemaFieldArgsCache[$key];
    }
}
