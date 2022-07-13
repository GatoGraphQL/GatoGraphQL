<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\ObjectSerialization\ObjectSerializationManagerInterface;
use PoP\ComponentModel\Resolvers\ResolverTypes;
use PoP\ComponentModel\TypeResolvers\DeprecatableInputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\DangerouslyNonSpecificScalarTypeScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoP\FieldQuery\FieldQueryInterpreter as UpstreamFieldQueryInterpreter;
use PoP\FieldQuery\QueryHelpers;
use PoP\FieldQuery\QuerySyntax;
use PoP\FieldQuery\QueryUtils;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\WithValueInterface;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\Root\App;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\FeedbackItemProviders\GenericFeedbackItemProvider;
use SplObjectStorage;
use stdClass;

class FieldQueryInterpreter extends UpstreamFieldQueryInterpreter implements FieldQueryInterpreterInterface
{
    /**
     * @var array<string, array>
     */
    private array $extractedFieldArgumentsCache = [];
    /**
     * @var array<string, array>
     */
    private array $extractedDirectiveArgumentsCache = [];
    /**
     * @var array<string, array<string, array<string, InputTypeResolverInterface>|null>>
     */
    private array $fieldArgumentNameTypeResolversCache = [];
    /**
     * @var array<string, array<string, array<string, InputTypeResolverInterface>>>
     */
    private array $directiveArgumentNameTypeResolversCache = [];
    /**
     * @var array<string, array>
     */
    private array $fieldArgumentNameDefaultValuesCache = [];
    /**
     * @var array<string, array>
     */
    private array $directiveArgumentNameDefaultValuesCache = [];
    /**
     * @var array<string, array|null>
     */
    private array $fieldSchemaDefinitionArgsCache = [];
    /**
     * @var array<string, array>
     */
    private array $directiveSchemaDefinitionArgsCache = [];

    private ?DangerouslyNonSpecificScalarTypeScalarTypeResolver $dangerouslyNonSpecificScalarTypeScalarTypeResolver = null;
    private ?InputCoercingServiceInterface $inputCoercingService = null;
    private ?ObjectSerializationManagerInterface $objectSerializationManager = null;

    final public function setDangerouslyNonSpecificScalarTypeScalarTypeResolver(DangerouslyNonSpecificScalarTypeScalarTypeResolver $dangerouslyNonSpecificScalarTypeScalarTypeResolver): void
    {
        $this->dangerouslyNonSpecificScalarTypeScalarTypeResolver = $dangerouslyNonSpecificScalarTypeScalarTypeResolver;
    }
    final protected function getDangerouslyNonSpecificScalarTypeScalarTypeResolver(): DangerouslyNonSpecificScalarTypeScalarTypeResolver
    {
        return $this->dangerouslyNonSpecificScalarTypeScalarTypeResolver ??= $this->instanceManager->getInstance(DangerouslyNonSpecificScalarTypeScalarTypeResolver::class);
    }
    final public function setInputCoercingService(InputCoercingServiceInterface $inputCoercingService): void
    {
        $this->inputCoercingService = $inputCoercingService;
    }
    final protected function getInputCoercingService(): InputCoercingServiceInterface
    {
        return $this->inputCoercingService ??= $this->instanceManager->getInstance(InputCoercingServiceInterface::class);
    }
    final public function setObjectSerializationManager(ObjectSerializationManagerInterface $objectSerializationManager): void
    {
        $this->objectSerializationManager = $objectSerializationManager;
    }
    final protected function getObjectSerializationManager(): ObjectSerializationManagerInterface
    {
        return $this->objectSerializationManager ??= $this->instanceManager->getInstance(ObjectSerializationManagerInterface::class);
    }

    protected function getVariablesHash(?array $variables): string
    {
        return (string)hash('crc32', json_encode($variables ?? []));
    }

    /**
     * Replace the fieldArgs in the field
     *
     * @param array<string, mixed> $fieldArgs
     */
    protected function replaceFieldArgs(string $field, array $fieldArgs): string
    {
        // Return a new field, replacing its fieldArgs (if any) with the provided ones
        // Used when validating a field and removing the fieldArgs that threw a warning
        list(
            $fieldArgsOpeningSymbolPos,
            $fieldArgsClosingSymbolPos
        ) = QueryHelpers::listFieldArgsSymbolPositions($field);

        // If it currently has fieldArgs, append the fieldArgs after the fieldName
        if ($fieldArgsOpeningSymbolPos !== false && $fieldArgsClosingSymbolPos !== false) {
            $fieldName = $this->getFieldName($field);
            return substr(
                $field,
                0,
                $fieldArgsOpeningSymbolPos
            ) .
            $this->getFieldArgsAsString($fieldArgs) .
            substr(
                $field,
                $fieldArgsClosingSymbolPos + strlen(QuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING)
            );
        }

        // Otherwise there are none. Then add the fieldArgs between the fieldName and whatever may come after
        // (alias, directives, or nothing)
        $fieldName = $this->getFieldName($field);
        return $fieldName . $this->getFieldArgsAsString($fieldArgs) . substr($field, strlen($fieldName));
    }

    /**
     * @return array<string, InputTypeResolverInterface>
     */
    protected function getDirectiveArgumentNameTypeResolvers(DirectiveResolverInterface $directiveResolver, RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        $relationalTypeResolverClass = get_class($relationalTypeResolver);
        $directiveResolverClass = get_class($directiveResolver);
        if (!isset($this->directiveArgumentNameTypeResolversCache[$directiveResolverClass][$relationalTypeResolverClass])) {
            $this->directiveArgumentNameTypeResolversCache[$directiveResolverClass][$relationalTypeResolverClass] = $this->doGetDirectiveArgumentNameTypeResolvers($directiveResolver, $relationalTypeResolver);
        }
        return $this->directiveArgumentNameTypeResolversCache[$directiveResolverClass][$relationalTypeResolverClass];
    }

    /**
     * @return array<string, InputTypeResolverInterface>
     */
    protected function doGetDirectiveArgumentNameTypeResolvers(DirectiveResolverInterface $directiveResolver, RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        // Get the fieldDirective argument types, to know to what type it will cast the value
        $directiveArgNameTypes = [];
        if ($directiveSchemaDefinitionArgs = $this->getDirectiveSchemaDefinitionArgs($directiveResolver, $relationalTypeResolver)) {
            foreach ($directiveSchemaDefinitionArgs as $directiveSchemaDefinitionArg) {
                $directiveArgNameTypes[$directiveSchemaDefinitionArg[SchemaDefinition::NAME]] = $directiveSchemaDefinitionArg[SchemaDefinition::TYPE_RESOLVER];
            }
        }
        return $directiveArgNameTypes;
    }

    protected function getDirectiveArgumentNameDefaultValues(DirectiveResolverInterface $directiveResolver, RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        $relationalTypeResolverClass = get_class($relationalTypeResolver);
        $directiveResolverClass = get_class($directiveResolver);
        if (!isset($this->directiveArgumentNameDefaultValuesCache[$directiveResolverClass][$relationalTypeResolverClass])) {
            $this->directiveArgumentNameDefaultValuesCache[$directiveResolverClass][$relationalTypeResolverClass] = $this->doGetDirectiveArgumentNameDefaultValues($directiveResolver, $relationalTypeResolver);
        }
        return $this->directiveArgumentNameDefaultValuesCache[$directiveResolverClass][$relationalTypeResolverClass];
    }

    protected function doGetDirectiveArgumentNameDefaultValues(DirectiveResolverInterface $directiveResolver, RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        // Get the directive arguments which have a default value
        // Set the missing InputObject as {} to give it a chance to set its default input field values
        $directiveArgNameDefaultValues = [];
        if ($directiveSchemaDefinitionArgs = $this->getDirectiveSchemaDefinitionArgs($directiveResolver, $relationalTypeResolver)) {
            foreach ($directiveSchemaDefinitionArgs as $directiveSchemaDefinitionArg) {
                if (\array_key_exists(SchemaDefinition::DEFAULT_VALUE, $directiveSchemaDefinitionArg)) {
                    // If it has a default value, set it
                    $directiveArgNameDefaultValues[$directiveSchemaDefinitionArg[SchemaDefinition::NAME]] = $directiveSchemaDefinitionArg[SchemaDefinition::DEFAULT_VALUE];
                } elseif (
                    // If it is a non-mandatory InputObject, set {}
                    // (If it is mandatory, don't set a value as to let the validation fail)
                    $directiveSchemaDefinitionArg[SchemaDefinition::TYPE_RESOLVER] instanceof InputObjectTypeResolverInterface
                    && !($directiveSchemaDefinitionArg[SchemaDefinition::MANDATORY] ?? false)
                ) {
                    $directiveArgNameDefaultValues[$directiveSchemaDefinitionArg[SchemaDefinition::NAME]] = new stdClass();
                }
            }
        }
        return $directiveArgNameDefaultValues;
    }

    protected function getFieldArgsSchemaDefinition(ObjectTypeResolverInterface $objectTypeResolver, FieldInterface $field): ?array
    {
        $objectTypeResolverClass = get_class($objectTypeResolver);
        if (!array_key_exists($field->asFieldOutputQueryString(), $this->fieldSchemaDefinitionArgsCache[$objectTypeResolverClass] ?? [])) {
            $fieldArgsSchemaDefinition = null;
            $fieldSchemaDefinition = $objectTypeResolver->getFieldSchemaDefinition($field);
            if ($fieldSchemaDefinition !== null) {
                $fieldArgsSchemaDefinition = $fieldSchemaDefinition[SchemaDefinition::ARGS] ?? [];
            }
            $this->fieldSchemaDefinitionArgsCache[$objectTypeResolverClass][$field->asFieldOutputQueryString()] = $fieldArgsSchemaDefinition;
        }
        return $this->fieldSchemaDefinitionArgsCache[$objectTypeResolverClass][$field->asFieldOutputQueryString()];
    }

    protected function getDirectiveSchemaDefinitionArgs(DirectiveResolverInterface $directiveResolver, RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        $relationalTypeResolverClass = get_class($relationalTypeResolver);
        $directiveResolverClass = get_class($directiveResolver);
        if (!isset($this->directiveSchemaDefinitionArgsCache[$directiveResolverClass][$relationalTypeResolverClass])) {
            $directiveSchemaDefinition = $directiveResolver->getDirectiveSchemaDefinition($relationalTypeResolver);
            $directiveSchemaDefinitionArgs = $directiveSchemaDefinition[SchemaDefinition::ARGS] ?? [];
            $this->directiveSchemaDefinitionArgsCache[$directiveResolverClass][$relationalTypeResolverClass] = $directiveSchemaDefinitionArgs;
        }
        return $this->directiveSchemaDefinitionArgsCache[$directiveResolverClass][$relationalTypeResolverClass];
    }

    /**
     * The value may be:
     * - A variable, if it starts with "$"
     * - An array, if it is surrounded with brackets and split with commas ([..., ..., ...])
     * - A number/string/field otherwise
     */
    public function maybeConvertFieldArgumentValue(mixed $fieldArgValue, ?array $variables = null): mixed
    {
        if (is_string($fieldArgValue)) {
            // The string "null" means `null`
            if ($fieldArgValue === 'null') {
                $fieldArgValue = null;
            } elseif ($fieldArgValue = trim($fieldArgValue)) {
                // Remove the white spaces before and after
                // Special case: when wrapping a string between quotes (eg: to avoid it being treated as a field, such as: posts(searchfor:"image(vertical)")),
                // the quotes are converted, from:
                // "value"
                // to:
                // "\"value\""
                // Transform back. Keep the quotes so that the string is still not converted to a field
                $fieldArgValue = stripcslashes($fieldArgValue);

                /**
                 * If it has quotes at the beginning and end, it's a string.
                 * Remove them, unless it could be interpreted as a field, then keep them.
                 *
                 * Explanation: If the string ends with "()" keep the quotes "", to make
                 * sure it is interpreted as a string, and it doesn't execute the field.
                 *
                 * eg: `{ posts(searchfor:"hel()") { id } }`
                 *
                 * @see https://github.com/leoloso/PoP/issues/743
                 */
                if ($this->isFieldArgumentValueWrappedWithStringSymbols((string) $fieldArgValue)) {
                    $strippedFieldArgValue = substr($fieldArgValue, strlen(QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING), strlen($fieldArgValue) - strlen(QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING) - strlen(QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING));
                    if (!$this->isFieldArgumentValueAField($strippedFieldArgValue)) {
                        $fieldArgValue = $strippedFieldArgValue;
                    }
                }

                // Chain functions. At any moment, if any of them throws an error, the result will be null so don't process anymore
                // First replace all variables
                if ($fieldArgValue = $this->maybeConvertFieldArgumentVariableValue($fieldArgValue, $variables)) {
                    // Then convert to arrays
                    $fieldArgValue = $this->maybeConvertFieldArgumentArrayOrObjectValue($fieldArgValue, $variables);
                }
            }
        }

        return $fieldArgValue;
    }

    protected function isFieldArgumentValueWrappedWithStringSymbols(string $fieldArgValue): bool
    {
        return
            substr($fieldArgValue, 0, strlen(QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING)) == QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING &&
            substr($fieldArgValue, -1 * strlen(QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING)) == QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING;
    }

    protected function maybeConvertFieldArgumentVariableValue(mixed $fieldArgValue, ?array $variables): mixed
    {
        // If it is a variable, retrieve the actual value from the request
        if ($this->isFieldArgumentValueAVariable($fieldArgValue)) {
            // Variables: allow to pass a field argument "key:$input", and then resolve it as ?variable[input]=value
            // Expected input is similar to GraphQL: https://graphql.org/learn/queries/#variables
            // If not passed the variables parameter, get param "variables" from the request
            if (is_null($variables)) {
                $variables = App::getState('variables');
            }
            $variableName = substr($fieldArgValue, strlen(QuerySyntax::SYMBOL_VARIABLE_PREFIX));
            if (isset($variables[$variableName])) {
                return $variables[$variableName];
            }
            // If the variable is not set, then show the error under entry "variableErrors"
            $this->getFeedbackMessageStore()->addQueryError(sprintf(
                $this->__('Variable \'%s\' is undefined', 'component-model'),
                $variableName
            ));
            return null;
        }

        return $fieldArgValue;
    }

    protected function maybeConvertFieldArgumentArrayValueFromStringToArray(string $fieldArgValue): mixed
    {
        // If surrounded by [...], it is an array
        if ($this->isFieldArgumentValueAnArrayRepresentedAsString($fieldArgValue)) {
            $arrayValue = substr($fieldArgValue, strlen(QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_OPENING), strlen($fieldArgValue) - strlen(QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_OPENING) - strlen(QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_CLOSING));
            // Check if an empty array was provided, as "[]" or "[ ]"
            if (trim($arrayValue) === '') {
                return [];
            }
            // Elements are split by ","
            $fieldArgValueElems = $this->getQueryParser()->splitElements($arrayValue, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_SEPARATOR, [QuerySyntax::SYMBOL_FIELDARGS_OPENING, QuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_OPENING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_OPENING], [QuerySyntax::SYMBOL_FIELDARGS_CLOSING, QuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_CLOSING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_CLOSING], QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING);
            // Watch out! If calling with value "[true,false]" it gets transformed to "[1,]" when passing the composed field around (it's converted back to string),
            // This must be transformed to array(true, false), however the last empty space is ignored by `splitElements`
            // So we handle these 2 cases (empty spaces at beginning and end of string) in an exceptional way
            if (substr($arrayValue, 0, strlen(QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_SEPARATOR)) == QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_SEPARATOR) {
                array_unshift($fieldArgValueElems, '');
            }
            if (substr($arrayValue, -1 * strlen(QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_SEPARATOR)) == QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_SEPARATOR) {
                $fieldArgValueElems[] = '';
            }
            // Iterate all the elements and assign them to the fieldArgValue variable
            // Arrays can be single or double-dimensional (key => value)
            // Each element can define which case it is
            // 1. Single dimensional: just output the value: [value]
            // 2. Double dimensional: output the key, then "=", then the value: [key=value]
            // These 2 can be combined, and the corresponding array will mix elements: [value1,key2=value2]
            $fieldArgValue = [];
            foreach ($fieldArgValueElems as $fieldArgValueElem) {
                $fieldArgValueElem = trim($fieldArgValueElem);
                $fieldArgValueElemComponents = $this->getQueryParser()->splitElements($fieldArgValueElem, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_KEYVALUEDELIMITER, [QuerySyntax::SYMBOL_FIELDARGS_OPENING, QuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_OPENING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_OPENING], [QuerySyntax::SYMBOL_FIELDARGS_CLOSING, QuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_CLOSING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_CLOSING], QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING);
                if (count($fieldArgValueElemComponents) === 1) {
                    // Remove the string quotes if it has them
                    $fieldArgValue[] = $this->maybeConvertFieldArgumentValue($fieldArgValueElemComponents[0]);
                } else {
                    $fieldArgValue[$fieldArgValueElemComponents[0]] = $this->maybeConvertFieldArgumentValue($fieldArgValueElemComponents[1]);
                }
            }
        }

        return $fieldArgValue;
    }

    protected function maybeConvertFieldArgumentObjectValueFromStringToObject(string $fieldArgValue): mixed
    {
        // If surrounded by {...}, it is an stdClass
        if ($this->isFieldArgumentValueAnObjectRepresentedAsString($fieldArgValue)) {
            $objectValue = substr($fieldArgValue, strlen(QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_OPENING), strlen($fieldArgValue) - strlen(QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_OPENING) - strlen(QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_CLOSING));
            $fieldArgValue = new stdClass();
            // Check if an empty object was provided, as "{}" or "{ }"
            if (trim($objectValue) === '') {
                return $fieldArgValue;
            }
            // Elements are split by ","
            $fieldArgValueElems = $this->getQueryParser()->splitElements($objectValue, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_SEPARATOR, [QuerySyntax::SYMBOL_FIELDARGS_OPENING, QuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_OPENING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_OPENING], [QuerySyntax::SYMBOL_FIELDARGS_CLOSING, QuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_CLOSING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_CLOSING], QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING);
            // Iterate all the elements and assign them to the fieldArgValue variable
            foreach ($fieldArgValueElems as $fieldArgValueElem) {
                $fieldArgValueElem = trim($fieldArgValueElem);
                $fieldArgValueElemComponents = $this->getQueryParser()->splitElements($fieldArgValueElem, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_KEYVALUEDELIMITER, [QuerySyntax::SYMBOL_FIELDARGS_OPENING, QuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_OPENING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_OPENING], [QuerySyntax::SYMBOL_FIELDARGS_CLOSING, QuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_CLOSING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_CLOSING], QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING);
                $fieldArgValueElemKey = $fieldArgValueElemComponents[0];
                $fieldArgValueElemValue = $fieldArgValueElemComponents[1];
                $fieldArgValue->$fieldArgValueElemKey = $this->maybeConvertFieldArgumentValue($fieldArgValueElemValue);
            }
        }

        return $fieldArgValue;
    }

    public function maybeConvertFieldArgumentArrayOrObjectValue(mixed $fieldArgValue, ?array $variables = null): mixed
    {
        if (is_string($fieldArgValue)) {
            $fieldArgValue = $this->maybeConvertFieldArgumentArrayValueFromStringToArray($fieldArgValue);
            // If still not converted, try to convert for object
            if (is_string($fieldArgValue)) {
                $fieldArgValue = $this->maybeConvertFieldArgumentObjectValueFromStringToObject($fieldArgValue);
            }
        }
        $isObject = $fieldArgValue instanceof stdClass;
        if (is_array($fieldArgValue) || $isObject) {
            // Resolve each element the same way
            // For object: Cast back and forth from array to stdClass
            $fieldOrDirectiveArgs = array_map(
                fn (mixed $arrayValueElem) => $this->maybeConvertFieldArgumentValue($arrayValueElem, $variables),
                (array) $fieldArgValue
            );
            if ($isObject) {
                return (object) $fieldOrDirectiveArgs;
            }
            return $fieldOrDirectiveArgs;
        }

        return $fieldArgValue;
    }

    /**
     * The value may be:
     * - A variable, if it starts with "$"
     * - A string, if it is surrounded with double quotes ("...")
     * - An array, if it is surrounded with brackets and split with commas ([..., ..., ...])
     * - A number
     * - A field
     */
    protected function maybeResolveFieldArgumentValueForObject(
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $object,
        mixed $fieldArgValue,
        ?array $variables,
        ?array $expressions,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        // If it is an array, apply this function on all elements
        if (is_array($fieldArgValue)) {
            return array_map(
                function ($fieldArgValueElem) use ($relationalTypeResolver, $object, $variables, $expressions, $objectTypeFieldResolutionFeedbackStore) {
                    return $this->maybeResolveFieldArgumentValueForObject($relationalTypeResolver, $object, $fieldArgValueElem, $variables, $expressions, $objectTypeFieldResolutionFeedbackStore);
                },
                (array)$fieldArgValue
            );
        }

        // Execute as expression
        if ($this->isFieldArgumentValueAnExpression($fieldArgValue)) {
            // Expressions: allow to pass a field argument "key:%input%", which is passed when executing the directive through $expressions
            // Trim it so that "%{ self }%" is equivalent to "%{self}%". This is needed to set expressions through Symfony's DependencyInjection component (since %...% is reserved for its own parameters!)
            return $this->resolveExpression(
                $relationalTypeResolver,
                $fieldArgValue,
                $expressions,
                $objectTypeFieldResolutionFeedbackStore,
            );
        }

        // @todo Watch out! Commented logic because composable fields are not used anymore, and didn't want to migrate $objectTypeResolver->collectFieldValidationErrors( using FieldInterface
        // if ($this->isFieldArgumentValueAField($fieldArgValue)) {
        //     // Execute as field
        //     // It is important to force the validation, because if a needed argument is provided with an error, it needs to be validated, casted and filtered out,
        //     // and if this wrong param is not "dynamic", then the validation would not take place
        //     $options = [
        //         AbstractRelationalTypeResolver::OPTION_VALIDATE_SCHEMA_ON_RESULT_ITEM => true,
        //     ];
        //     $resolvedValue = $relationalTypeResolver->resolveValue(
        //         $object,
        //         (string)$fieldArgValue,
        //         $variables,
        //         $expressions,
        //         $objectTypeFieldResolutionFeedbackStore,
        //         $options
        //     );
        //     if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
        //         return null;
        //     }
        //     return $resolvedValue;
        // }

        return $fieldArgValue;
    }

    public function resolveExpression(
        RelationalTypeResolverInterface $relationalTypeResolver,
        mixed $fieldArgValue,
        ?array $expressions,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        // Expressions: allow to pass a field argument "key:%input%", which is passed when executing the directive through $expressions

        /**
         * Switched from "%{...}%" to "$__..."
         * @todo Convert expressions from "$__" to "$"
         */
        // // Trim it so that "%{ self }%" is equivalent to "%{self}%". This is needed to set expressions through Symfony's DependencyInjection component (since %...% is reserved for its own parameters!)
        // $expressionName = trim(substr($fieldArgValue, strlen(QuerySyntax::SYMBOL_EXPRESSION_OPENING), strlen($fieldArgValue) - strlen(QuerySyntax::SYMBOL_EXPRESSION_OPENING) - strlen(QuerySyntax::SYMBOL_EXPRESSION_CLOSING)));
        $expressionName = substr($fieldArgValue, strlen('$__'));
        if (!isset($expressions[$expressionName])) {
            // If the expression is not set, then show the error under entry "expressionErrors"
            // @todo Temporarily hack fix: Need to pass astNode but don't have it, so commented
            // $objectTypeFieldResolutionFeedbackStore->addError(
            //     new ObjectTypeFieldResolutionFeedback(
            //         new FeedbackItemResolution(
            //             ErrorFeedbackItemProvider::class,
            //             ErrorFeedbackItemProvider::E14,
            //             [
            //                 $expressionName,
            //             ]
            //         ),
            //         LocationHelper::getNonSpecificLocation(),
            //         $relationalTypeResolver,
            //     )
            // );
            return null;
        }
        return $expressions[$expressionName];
    }

    protected function collectFieldArgumentValueErrorQualifiedEntriesForSchema(
        ObjectTypeResolverInterface $objectTypeResolver,
        mixed $fieldArgValue,
        array $variables,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        // If it is an array, apply this function on all elements
        if (is_array($fieldArgValue)) {
            foreach ($fieldArgValue as $fieldArgValueElem) {
                $this->collectFieldArgumentValueErrorQualifiedEntriesForSchema($objectTypeResolver, $fieldArgValueElem, $variables, $objectTypeFieldResolutionFeedbackStore);
            }
            return;
        }

        // @todo Watch out! Commented logic because composable fields are not used anymore, and didn't want to migrate $objectTypeResolver->collectFieldValidationErrors( using FieldInterface
        // // If the result fieldArgValue is a string (i.e. not numeric), and it has brackets (...),
        // // and is not wrapped with quotes, then it is a field. Validate it and resolve it
        // if (!empty($fieldArgValue) && is_string($fieldArgValue) && !is_numeric($fieldArgValue) && !$this->isFieldArgumentValueWrappedWithStringSymbols((string) $fieldArgValue)) {
        //     $fieldArgValue = (string)$fieldArgValue;
        //     // If it has the fieldArg brackets, and the last bracket is at the end, then it's a field!
        //     list(
        //         $fieldArgsOpeningSymbolPos,
        //         $fieldArgsClosingSymbolPos
        //     ) = QueryHelpers::listFieldArgsSymbolPositions($fieldArgValue);

        //     // If there is no "(" or ")", or if the ")" is not at the end, of if the "(" is at the beginning, then it's simply a string
        //     if ($fieldArgsClosingSymbolPos !== (strlen($fieldArgValue) - strlen(QuerySyntax::SYMBOL_FIELDARGS_CLOSING)) || $fieldArgsOpeningSymbolPos === false || $fieldArgsOpeningSymbolPos === 0) {
        //         return;
        //     }

        //     // If it reached here, it's a field! Validate it, or show an error
        //     $objectTypeResolver->collectFieldValidationErrors($fieldArgValue, $variables, $objectTypeFieldResolutionFeedbackStore);
        //     return;
        // }
    }

    protected function collectFieldArgumentValueWarningQualifiedEntriesForSchema(
        ObjectTypeResolverInterface $objectTypeResolver,
        mixed $fieldArgValue,
        array $variables,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        // If it is an array, apply this function on all elements
        if (is_array($fieldArgValue)) {
            foreach ($fieldArgValue as $fieldArgValueElem) {
                $this->collectFieldArgumentValueWarningQualifiedEntriesForSchema($objectTypeResolver, $fieldArgValueElem, $variables, $objectTypeFieldResolutionFeedbackStore);
            }
            return;
        }

        // @todo Watch out! Commented logic because composable fields are not used anymore, and didn't want to migrate $objectTypeResolver->collectFieldValidationErrors( using FieldInterface
        // // If the result fieldArgValue is a field, then validate it and resolve it
        // if ($this->isFieldArgumentValueAField($fieldArgValue)) {
        //     $objectTypeResolver->collectFieldValidationWarnings($fieldArgValue, $variables, $objectTypeFieldResolutionFeedbackStore);
        //     return;
        // }
    }

    protected function collectFieldArgumentValueDeprecationQualifiedEntriesForSchema(
        ObjectTypeResolverInterface $objectTypeResolver,
        mixed $fieldArgValue,
        array $variables,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        // If it is an array, apply this function on all elements
        if (is_array($fieldArgValue)) {
            foreach ($fieldArgValue as $fieldArgValueElem) {
                $this->collectFieldArgumentValueDeprecationQualifiedEntriesForSchema($objectTypeResolver, $fieldArgValueElem, $variables, $objectTypeFieldResolutionFeedbackStore);
            }
            return;
        }

        // @todo Watch out! Commented logic because composable fields are not used anymore, and didn't want to migrate $objectTypeResolver->collectFieldValidationErrors( using FieldInterface
        // // If the result fieldArgValue is a field, then validate it and resolve it
        // if ($this->isFieldArgumentValueAField($fieldArgValue)) {
        //     $objectTypeResolver->collectFieldDeprecations($fieldArgValue, $variables, $objectTypeFieldResolutionFeedbackStore);
        //     return;
        // }
    }

    protected function serializeObject(object $object): string
    {
        if ($object instanceof WithValueInterface) {
            return $object->getValue();
        }
        return $this->getObjectSerializationManager()->serialize($object);
    }
}
