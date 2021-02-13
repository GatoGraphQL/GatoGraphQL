<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ConditionalOnEnvironment\MultipleQueryExecution\SchemaServices\DirectiveResolvers;

use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Feedback\Tokens;
use GraphQLByPoP\GraphQLQuery\Schema\QuerySymbols;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\DirectiveResolvers\AbstractGlobalDirectiveResolver;

/**
 * @export directive, to make the value of a leaf field available through a variable.
 *
 * It works only with the following conditions:
 *
 * 1. The name of the variable must start with "_": to tell the GraphQL engine that this variable is resolved on runtime, not on query parsing time
 * 2. A field `self` from the Root type must be used to control that the field exporting the variable is executed before the field reading the variable
 * 3. The variable must receive a default value, even if it won't be used: this prevents the parser from throwing a "variable has not been set" error
 *
 * To understand how to control the order in which fields are executed, read:
 * @see https://leoloso.com/posts/demonstrating-pop-api-graphql-on-steroids/
 *
 * Examples:
 *
 * The query below works, because:
 *
 * 1. Variable is called "_me", starting with "_"
 * 2. Field `self` (on type Root) makes field `posts(author:$_me)` (on type Root) be executed after field `id @export(as:"_me")` (on type User)
 * 3. Variable has default value "-1"
 *
 * ```graphql
 * query GetMyPosts($_me:ID=-1) {
 *   me {
 *     id @export(as:"_me")
 *   }
 *   self {
 *     posts(author:$_me) {
 *       id
 *       title
 *     }
 *   }
 * }
 * ```
 *
 * The query below does NOT work, because it doesn't satisfy any of the 3 conditions (failing at 1 is already enough for it to not work)
 *
 * ```graphql
 * query GetMyPosts($me:ID) {
 *   me {
 *     id @export(as:"me")
 *   }
 *   posts(author:$me) {
 *     id
 *     title
 *   }
 * }
 * ```
 */
class ExportDirectiveResolver extends AbstractGlobalDirectiveResolver
{
    public const DIRECTIVE_NAME = 'export';

    public static function getDirectiveName(): string
    {
        return self::DIRECTIVE_NAME;
    }

    /**
     * Export the value of a field, assigning it to a variable.
     *
     * It works with a single value. This query:
     *
     * ```graphql
     * query {
     *   me {
     *     id @export(as:"myID")
     *   }
     * }
     * ```
     *
     * ...exports variable $myID with the user ID as value.
     *
     * If several values are exported, then the variable will contain an array with all of them. This query:
     *
     * ```graphql
     * query {
     *   posts {
     *     id @export(as:"postIDs")
     *   }
     * }
     * ```
     *
     * ... exports variable $postIDs as an array with the IDs of all posts
     *
     * If several fields are exported with the same variable name on the same object, then the variable is assigned a dictionary of field/value. This query:
     *
     * ```graphql
     * query {
     *   me {
     *     id @export(as:"myData")
     *     name @export(as:"myData")
     *   }
     * }
     * ```
     *
     * ... exports variable $myData as a dictionary {"id": user ID, "name": user name}
     *
     * If over an array of objects, several fields are exported with the same variable name, then the variable is assigned an array containing dictionaries of field/value. This query:
     *
     * ```graphql
     * query {
     *   posts {
     *     id @export(as:"postIDsAndTitles")
     *     title @export(as:"postIDsAndTitles")
     *   }
     * }
     * ```
     *
     * ... exports variable $postIDsAndTitles as an array, where each item is a dictionary {"id": post ID, "title": post title}
     *
     * @param TypeResolverInterface $typeResolver
     * @param array $idsDataFields
     * @param array $succeedingPipelineIDsDataFields
     * @param array $succeedingPipelineDirectiveResolverInstances
     * @param array $resultIDItems
     * @param array $unionDBKeyIDs
     * @param array $dbItems
     * @param array $previousDBItems
     * @param array $variables
     * @param array $_messages
     * @param array $dbErrors
     * @param array $dbWarnings
     * @param array $dbDeprecations
     * @param array $schemaErrors
     * @param array $schemaWarnings
     * @param array $schemaDeprecations
     * @return void
     */
    public function resolveDirective(
        TypeResolverInterface $typeResolver,
        array &$idsDataFields,
        array &$succeedingPipelineIDsDataFields,
        array &$succeedingPipelineDirectiveResolverInstances,
        array &$resultIDItems,
        array &$unionDBKeyIDs,
        array &$dbItems,
        array &$previousDBItems,
        array &$variables,
        array &$messages,
        array &$dbErrors,
        array &$dbWarnings,
        array &$dbDeprecations,
        array &$dbNotices,
        array &$dbTraces,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations,
        array &$schemaNotices,
        array &$schemaTraces
    ): void {
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $ids = array_keys($idsDataFields);

        /**
         * Single object. 2 cases:
         *
         * 1. Single value: when there is a single field
         * 2. Dictionary: otherwise
         */
        if (count($ids) == 1) {
            $id = $ids[0];
            $fields = $idsDataFields[(string)$id]['direct'];
            /**
             * Case 1: Single field => single value:
             *
             * ```graphql
             * query {
             *   me {
             *     id @export(as:"myID")
             *   }
             * }
             * ```
             */
            if (count($fields) == 1) {
                $field = $fields[0];
                $fieldOutputKey = $fieldQueryInterpreter->getFieldOutputKey($field);
                $value = $dbItems[(string)$id][$fieldOutputKey];
                $this->setVariable($variables, $value, $schemaWarnings);
                return;
            }

            /**
             * Case 2: Multiple fields => dictionary:
             *
             * ```graphql
             * query {
             *   me {
             *     id @export(as:"myData")
             *     name @export(as:"myData")
             *   }
             * }
             * ```
             */
            $value = [];
            foreach ($fields as $field) {
                $fieldOutputKey = $fieldQueryInterpreter->getFieldOutputKey($field);
                $value[$fieldOutputKey] = $dbItems[(string)$id][$fieldOutputKey];
            }
            $this->setVariable($variables, $value, $schemaWarnings);
            return;
        }

        /**
         * Multiple objects. 2 cases:
         *
         * 1. Array of values: When all objects have a single field, and this field is the same for all objects
         * 2. Array of dictionaries: Otherwise
         */
        $value = [];
        $allFields = array_unique(GeneralUtils::arrayFlatten(array_map(
            function ($idDataFields) {
                return $idDataFields['direct'];
            },
            $idsDataFields
        )));

        /**
         * Case 1: Array of values
         *
         * ```graphql
         * query {
         *   posts {
         *     id @export(as:"postIDs")
         *   }
         * }
         * ```
         */
        if (count($allFields) == 1) {
            $field = $allFields[0];
            $fieldOutputKey = $fieldQueryInterpreter->getFieldOutputKey($field);
            foreach ($ids as $id) {
                $value[] = $dbItems[(string)$id][$fieldOutputKey];
            }
            $this->setVariable($variables, $value, $schemaWarnings);
            return;
        }

        /**
         * Case 2: Array of dictionaries:
         *
         * ```graphql
         * query {
         *   posts {
         *     id @export(as:"postIDsAndTitles")
         *     title @export(as:"postIDsAndTitles")
         *   }
         * }
         * ```
         */
        foreach ($idsDataFields as $id => $dataFields) {
            $dictionary = [];
            foreach ($dataFields['direct'] as $field) {
                $fieldOutputKey = $fieldQueryInterpreter->getFieldOutputKey($field);
                $dictionary[$fieldOutputKey] = $dbItems[(string)$id][$fieldOutputKey];
            }
            $value[] = $dictionary;
        }
        $this->setVariable($variables, $value, $schemaWarnings);
        return;
    }

    protected function setVariable(array &$variables, $value, array &$schemaWarnings): void
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $variableName = $this->directiveArgsForSchema['as'];
        // If the variable already exists, then throw a warning and do nothing
        if (isset($variables[$variableName])) {
            $schemaWarnings[] = [
                Tokens::PATH => [$this->directive],
                Tokens::MESSAGE => sprintf(
                    $translationAPI->__('Cannot export variable with name \'%s\' because this variable has already been set', 'component-model'),
                    $variableName
                ),
            ];
            return;
        }
        $variables[$variableName] = $value;
    }

    public function getSchemaDirectiveDescription(TypeResolverInterface $typeResolver): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('Exports a field value as a variable', 'graphql-server');
    }
    public function getSchemaDirectiveArgs(TypeResolverInterface $typeResolver): array
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return [
            [
                SchemaDefinition::ARGNAME_NAME => 'as',
                SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                SchemaDefinition::ARGNAME_DESCRIPTION => sprintf(
                    $translationAPI->__('Name of the variable. It must start with \'%s\', or the directive will not work', 'graphql-server'),
                    QuerySymbols::VARIABLE_AS_EXPRESSION_NAME_PREFIX
                ),
                SchemaDefinition::ARGNAME_MANDATORY => true,
            ],
        ];
    }
}
