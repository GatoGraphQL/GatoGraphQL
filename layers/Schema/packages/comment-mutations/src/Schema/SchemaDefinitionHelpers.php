<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations\Schema;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\CommentMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\Comments\ComponentConfiguration;

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

            }
            self::$schemaFieldArgsCache[$key] = $schemaFieldArgs;
        }
        return self::$schemaFieldArgsCache[$key];
    }
}
