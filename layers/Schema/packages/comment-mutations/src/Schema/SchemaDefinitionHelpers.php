<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations\Schema;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CommentMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\Comments\ComponentConfiguration;
use PoPSchema\Users\Facades\UserTypeAPIFacade;

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
                $vars = ApplicationState::getVars();
                $defaultAuthorName = null;
                $defaultAuthorEmail = null;
                $defaultAuthorURL = null;
                // If the user is logged-in, take his/her properties to set as default
                if ($vars['global-userstate']['is-user-logged-in']) {
                    $userTypeAPI = UserTypeAPIFacade::getInstance();
                    $userID = $vars['global-userstate']['current-user-id'];
                    $defaultAuthorName = $userTypeAPI->getUserDisplayName($userID);
                    $defaultAuthorEmail = $userTypeAPI->getUserEmail($userID);
                    $defaultAuthorURL = $userTypeAPI->getUserURL($userID);
                }
                $schemaFieldArgs[] = [
                    SchemaDefinition::ARGNAME_NAME => MutationInputProperties::AUTHOR_NAME,
                    SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                    SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The comment author\'s name', 'comment-mutations'),
                    SchemaDefinition::ARGNAME_DEFAULT_VALUE => $defaultAuthorName,
                ];
                $schemaFieldArgs[] = [
                    SchemaDefinition::ARGNAME_NAME => MutationInputProperties::AUTHOR_EMAIL,
                    SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                    SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The comment author\'s email', 'comment-mutations'),
                    SchemaDefinition::ARGNAME_DEFAULT_VALUE => $defaultAuthorEmail,
                ];
                $schemaFieldArgs[] = [
                    SchemaDefinition::ARGNAME_NAME => MutationInputProperties::AUTHOR_URL,
                    SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                    SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The comment author\'s site URL', 'comment-mutations'),
                    SchemaDefinition::ARGNAME_DEFAULT_VALUE => $defaultAuthorURL,
                ];
            }
            self::$schemaFieldArgsCache[$key] = $schemaFieldArgs;
        }
        return self::$schemaFieldArgsCache[$key];
    }
}
