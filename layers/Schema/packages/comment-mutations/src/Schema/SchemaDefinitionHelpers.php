<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations\Schema;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CommentMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\CommentMutations\ComponentConfiguration;

class SchemaDefinitionHelpers
{
    private static array $schemaFieldArgsCache = [];

    public static function getAddCommentToCustomPostSchemaFieldArgs(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $fieldName,
        bool $addCustomPostID,
        bool $addParentCommentID,
        bool $isParentCommentMandatory = false
    ): array {
        $key = get_class($relationalTypeResolver) . '-' . $fieldName;
        if (!isset(self::$schemaFieldArgsCache[$key])) {
            $translationAPI = TranslationAPIFacade::getInstance();
            $schemaFieldArgs = [
                [
                    SchemaDefinition::ARGNAME_NAME => MutationInputProperties::COMMENT,
                    SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                    SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The comment to add', 'comment-mutations'),
                    SchemaDefinition::ARGNAME_MANDATORY => true,
                ],
            ];
            if ($addParentCommentID) {
                $schemaFieldArgs[] = [
                    SchemaDefinition::ARGNAME_NAME => MutationInputProperties::PARENT_COMMENT_ID,
                    SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_ID,
                    SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The ID of the parent comment', 'comment-mutations'),
                    SchemaDefinition::ARGNAME_MANDATORY => $isParentCommentMandatory,
                ];
            }
            if ($addCustomPostID) {
                $schemaFieldArgs[] = [
                    SchemaDefinition::ARGNAME_NAME => MutationInputProperties::CUSTOMPOST_ID,
                    SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_ID,
                    SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The ID of the custom post to add a comment to', 'comment-mutations'),
                    SchemaDefinition::ARGNAME_MANDATORY => true,
                ];
            }
            if (!ComponentConfiguration::mustUserBeLoggedInToAddComment()) {
                $areFieldsMandatory = ComponentConfiguration::requireCommenterNameAndEmail();
                $schemaFieldArgs[] = [
                    SchemaDefinition::ARGNAME_NAME => MutationInputProperties::AUTHOR_NAME,
                    SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                    SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The comment author\'s name', 'comment-mutations'),
                    SchemaDefinition::ARGNAME_MANDATORY => $areFieldsMandatory,
                ];
                $schemaFieldArgs[] = [
                    SchemaDefinition::ARGNAME_NAME => MutationInputProperties::AUTHOR_EMAIL,
                    SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_EMAIL,
                    SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The comment author\'s email', 'comment-mutations'),
                    SchemaDefinition::ARGNAME_MANDATORY => $areFieldsMandatory,
                ];
                $schemaFieldArgs[] = [
                    SchemaDefinition::ARGNAME_NAME => MutationInputProperties::AUTHOR_URL,
                    SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_URL,
                    SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The comment author\'s site URL', 'comment-mutations'),
                ];
            }
            self::$schemaFieldArgsCache[$key] = $schemaFieldArgs;
        }
        return self::$schemaFieldArgsCache[$key];
    }
}
