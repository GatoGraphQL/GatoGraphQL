<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations\Schema;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\CommentMutations\MutationResolvers\MutationInputProperties;

class SchemaDefinitionHelpers
{
    private static array $schemaFieldArgsCache = [];

    public static function getAddCommentToCustomPostSchemaFieldArgs(
        TypeResolverInterface $typeResolver,
        string $fieldName,
        bool $addCustomPostID,
        bool $addParentCommentID,
        bool $isParentCommentMandatory = false
    ): array {
        $key = get_class($typeResolver) . '-' . $fieldName;
        if (!isset(self::$schemaFieldArgsCache[$key])) {
            $translationAPI = TranslationAPIFacade::getInstance();
            self::$schemaFieldArgsCache[$key] = array_merge(
                $addCustomPostID ? [
                    [
                        SchemaDefinition::ARGNAME_NAME => MutationInputProperties::CUSTOMPOST_ID,
                        SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_ID,
                        SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The ID of the custom post to add a comment to', 'comment-mutations'),
                        SchemaDefinition::ARGNAME_MANDATORY => true,
                    ],
                ] : [],
                [
                    [
                        SchemaDefinition::ARGNAME_NAME => MutationInputProperties::COMMENT,
                        SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                        SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The comment to add', 'comment-mutations'),
                        SchemaDefinition::ARGNAME_MANDATORY => true,
                    ],
                ],
                $addParentCommentID ? [
                    [
                        SchemaDefinition::ARGNAME_NAME => MutationInputProperties::PARENT_COMMENT_ID,
                        SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_ID,
                        SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The ID of the parent comment', 'comment-mutations'),
                        SchemaDefinition::ARGNAME_MANDATORY => $isParentCommentMandatory,
                    ],
                ] : []
            );
        }
        return self::$schemaFieldArgsCache[$key];
    }
}
