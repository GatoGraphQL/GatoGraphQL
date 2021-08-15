<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\ErrorHandling\ErrorDataTokens;
use PoP\ComponentModel\Feedback\Tokens;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Resolvers\ResolverTypes;
use PoP\ComponentModel\Schema\FeedbackMessageStoreInterface;
use PoP\ComponentModel\Schema\FieldQueryUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\FieldQuery\FeedbackMessageStoreInterface as UpstreamFeedbackMessageStoreInterface;
use PoP\FieldQuery\QueryHelpers;
use PoP\FieldQuery\QuerySyntax;
use PoP\FieldQuery\QueryUtils;
use PoP\QueryParsing\QueryParserInterface;
use PoP\Translation\TranslationAPIInterface;

class FieldQueryInterpreter extends \PoP\FieldQuery\FieldQueryInterpreter implements FieldQueryInterpreterInterface
{
    // Cache the output from functions
    /**
     * @var array<string, array>
     */
    private array $extractedStaticFieldArgumentsCache = [];
    /**
     * @var array<string, array>
     */
    private array $extractedFieldArgumentsCache = [];
    /**
     * @var array<string, array>
     */
    private array $extractedDirectiveArgumentsCache = [];
    /**
     * @var array<string, array>
     */
    private array $extractedFieldArgumentErrorsCache = [];
    /**
     * @var array<string, array>
     */
    private array $extractedFieldArgumentWarningsCache = [];
    /**
     * @var array<string, array>
     */
    private array $extractedDirectiveArgumentErrorsCache = [];
    /**
     * @var array<string, array>
     */
    private array $extractedDirectiveArgumentWarningsCache = [];
    /**
     * @var array<string, array>
     */
    private array $fieldArgumentNameTypesCache = [];
    /**
     * @var array<string, array>
     */
    private array $directiveArgumentNameTypesCache = [];
    /**
     * @var array<string, array>
     */
    private array $fieldArgumentNameDefaultValuesCache = [];
    /**
     * @var array<string, array>
     */
    private array $directiveArgumentNameDefaultValuesCache = [];
    /**
     * @var array<string, array>
     */
    private array $fieldSchemaDefinitionArgsCache = [];
    /**
     * @var array<string, array>
     */
    private array $directiveSchemaDefinitionArgsCache = [];

    /**
     * @var array<string,array<string,string>>
     */
    private array $fieldOutputKeysByTypeAndField = [];
    /**
     * @var array<string,array<string,string>>
     */
    private array $fieldsByTypeAndFieldOutputKey = [];

    public function __construct(
        TranslationAPIInterface $translationAPI,
        UpstreamFeedbackMessageStoreInterface $feedbackMessageStore,
        protected TypeCastingExecuterInterface $typeCastingExecuter,
        protected InstanceManagerInterface $instanceManager,
        QueryParserInterface $queryParser,
    ) {
        parent::__construct($translationAPI, $feedbackMessageStore, $queryParser);
    }

    final public function getUniqueFieldOutputKey(TypeResolverInterface $typeResolver, string $field): string
    {
        return $this->getUniqueFieldOutputKeyByTypeOutputName(
            $typeResolver->getTypeOutputName(),
            $field
        );
    }

    final public function getUniqueFieldOutputKeyByTypeResolverClass(string $typeResolverClass, string $field): string
    {
        /**
         * @var TypeResolverInterface
         */
        $typeResolver = $this->instanceManager->getInstance($typeResolverClass);
        return $this->getUniqueFieldOutputKey(
            $typeResolver,
            $field
        );
    }

    /**
     * Obtain a unique fieldOutputKey for the field, for the type.
     * This is to avoid overriding a previous value with the same alias,
     * but placed on a different iteration:
     *
     *   ```graphql
     *   {
     *     posts {
     *       title
     *       self {
     *         title: excerpt
     *       }
     *     }
     *   ```
     *
     * In this query, the field "excerpt" has alias "title", and would override
     * the title value from the previous iteration.
     *
     * By keeping a registry of fields to fieldOutputNames, we can always provide
     * a unique name, and avoid overriding the value.
     */
    public function getUniqueFieldOutputKeyByTypeOutputName(string $typeOutputName, string $field): string
    {
        // If a fieldOutputKey has already been created for this field, retrieve it
        if ($fieldOutputKey = $this->fieldOutputKeysByTypeAndField[$typeOutputName][$field] ?? null) {
            return $fieldOutputKey;
        }
        $fieldOutputKey = $this->getFieldOutputKey($field);
        if (!isset($this->fieldsByTypeAndFieldOutputKey[$typeOutputName][$fieldOutputKey])) {
            $this->fieldsByTypeAndFieldOutputKey[$typeOutputName][$fieldOutputKey] = $field;
            $this->fieldOutputKeysByTypeAndField[$typeOutputName][$field] = $fieldOutputKey;
            return $fieldOutputKey;
        }
        // This fieldOutputKey already exists for a different field,
        // then create a counter and iterate until it doesn't exist anymore
        $counter = 0;
        while (isset($this->fieldsByTypeAndFieldOutputKey[$typeOutputName][$fieldOutputKey . '-' . $counter])) {
            $counter++;
        }
        $fieldOutputKey = $fieldOutputKey . '-' . $counter;
        $this->fieldsByTypeAndFieldOutputKey[$typeOutputName][$fieldOutputKey] = $field;
        $this->fieldOutputKeysByTypeAndField[$typeOutputName][$field] = $fieldOutputKey;
        return $fieldOutputKey;
    }

    /**
     * Extract field args without using the schema.
     * It is needed to find out which fieldResolver will process a field,
     * where we can't depend on the schema since this one needs to know
     * who the fieldResolver is, creating an infitine loop.
     *
     * Directive arguments have the same syntax as field arguments,
     * so simply re-utilize the corresponding function for field arguments.
     */
    public function extractStaticDirectiveArguments(string $directive, ?array $variables = null): array
    {
        return $this->extractStaticFieldArguments($directive, $variables);
    }

    protected function getVariablesHash(?array $variables): string
    {
        return (string)hash('crc32', json_encode($variables ?? []));
    }

    /**
     * Extract field args without using the schema.
     * It is needed to find out which fieldResolver will process a field,
     * where we can't depend on the schema since this one needs to know
     * who the fieldResolver is, creating an infitine loop.
     */
    public function extractStaticFieldArguments(string $field, ?array $variables = null): array
    {
        $variablesHash = $this->getVariablesHash($variables);
        if (!isset($this->extractedStaticFieldArgumentsCache[$field][$variablesHash])) {
            $this->extractedStaticFieldArgumentsCache[$field][$variablesHash] = $this->doExtractStaticFieldArguments($field, $variables);
        }
        return $this->extractedStaticFieldArgumentsCache[$field][$variablesHash];
    }

    protected function doExtractStaticFieldArguments(string $field, ?array $variables): array
    {
        $fieldArgs = [];
        // Extract the args from the string into an array
        if ($fieldArgsStr = $this->getFieldArgs($field)) {
            // Remove the opening and closing brackets
            $fieldArgsStr = substr($fieldArgsStr, strlen(QuerySyntax::SYMBOL_FIELDARGS_OPENING), strlen($fieldArgsStr) - strlen(QuerySyntax::SYMBOL_FIELDARGS_OPENING) - strlen(QuerySyntax::SYMBOL_FIELDARGS_CLOSING));
            // Remove the white spaces before and after
            if ($fieldArgsStr = trim($fieldArgsStr)) {
                // Iterate all the elements, and extract them into the array
                if ($fieldArgElems = $this->queryParser->splitElements($fieldArgsStr, QuerySyntax::SYMBOL_FIELDARGS_ARGSEPARATOR, [QuerySyntax::SYMBOL_FIELDARGS_OPENING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_OPENING], [QuerySyntax::SYMBOL_FIELDARGS_CLOSING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_CLOSING], QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING)) {
                    for ($i = 0; $i < count($fieldArgElems); $i++) {
                        $fieldArg = $fieldArgElems[$i];
                        // If there is no separator, then skip this arg, since it is not static (without the schema, we can't know which fieldArgName it is)
                        $separatorPos = QueryUtils::findFirstSymbolPosition(
                            $fieldArg,
                            QuerySyntax::SYMBOL_FIELDARGS_ARGKEYVALUESEPARATOR,
                            [QuerySyntax::SYMBOL_FIELDARGS_OPENING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_OPENING],
                            [QuerySyntax::SYMBOL_FIELDARGS_CLOSING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_CLOSING],
                        );
                        if ($separatorPos === false) {
                            continue;
                        }
                        $fieldArgName = trim(substr($fieldArg, 0, $separatorPos));
                        $fieldArgValue = trim(substr($fieldArg, $separatorPos + strlen(QuerySyntax::SYMBOL_FIELDARGS_ARGKEYVALUESEPARATOR)));
                        // If the field is an array in its string representation, convert it to array
                        $fieldArgValue = $this->maybeConvertFieldArgumentValue($fieldArgValue, $variables);
                        $fieldArgs[$fieldArgName] = $fieldArgValue;
                    }
                }
            }
        }

        return $fieldArgs;
    }

    public function extractDirectiveArguments(
        DirectiveResolverInterface $directiveResolver,
        TypeResolverInterface $typeResolver,
        string $fieldDirective,
        ?array $variables = null,
        ?array &$schemaErrors = null,
        ?array &$schemaWarnings = null,
    ): array {
        $variablesHash = $this->getVariablesHash($variables);
        if (!isset($this->extractedDirectiveArgumentsCache[get_class($typeResolver)][$fieldDirective][$variablesHash])) {
            $fieldSchemaWarnings = $fieldSchemaErrors = [];
            $this->extractedDirectiveArgumentsCache[get_class($typeResolver)][$fieldDirective][$variablesHash] = $this->doExtractDirectiveArguments(
                $directiveResolver,
                $typeResolver,
                $fieldDirective,
                $variables,
                $fieldSchemaErrors,
                $fieldSchemaWarnings,
            );
            $this->extractedDirectiveArgumentErrorsCache[get_class($typeResolver)][$fieldDirective][$variablesHash] = $fieldSchemaErrors;
            $this->extractedDirectiveArgumentWarningsCache[get_class($typeResolver)][$fieldDirective][$variablesHash] = $fieldSchemaWarnings;
        }
        // Integrate the errors/warnings too
        if ($schemaErrors !== null) {
            $schemaErrors = array_merge(
                $schemaErrors,
                $this->extractedDirectiveArgumentErrorsCache[get_class($typeResolver)][$fieldDirective][$variablesHash]
            );
        }
        if ($schemaWarnings !== null) {
            $schemaWarnings = array_merge(
                $schemaWarnings,
                $this->extractedDirectiveArgumentWarningsCache[get_class($typeResolver)][$fieldDirective][$variablesHash]
            );
        }
        return $this->extractedDirectiveArgumentsCache[get_class($typeResolver)][$fieldDirective][$variablesHash];
    }

    protected function doExtractDirectiveArguments(
        DirectiveResolverInterface $directiveResolver,
        TypeResolverInterface $typeResolver,
        string $fieldDirective,
        ?array $variables,
        array &$schemaErrors,
        array &$schemaWarnings,
    ): array {
        $directiveArgumentNameDefaultValues = $this->getDirectiveArgumentNameDefaultValues($directiveResolver, $typeResolver);
        // Iterate all the elements, and extract them into the array
        if ($directiveArgElems = QueryHelpers::getFieldArgElements($this->getFieldDirectiveArgs($fieldDirective))) {
            $directiveArgumentNameTypes = $this->getDirectiveArgumentNameTypes($directiveResolver, $typeResolver);
            $orderedDirectiveArgNamesEnabled = $directiveResolver->enableOrderedSchemaDirectiveArgs($typeResolver);
            return $this->extractAndValidateFielOrDirectiveArguments(
                $fieldDirective,
                $directiveArgElems,
                $orderedDirectiveArgNamesEnabled,
                $directiveArgumentNameTypes,
                $directiveArgumentNameDefaultValues,
                $variables,
                $schemaErrors,
                $schemaWarnings,
                ResolverTypes::DIRECTIVE
            );
        }

        return $directiveArgumentNameDefaultValues;
    }

    /**
     * Extract the arguments for either the field or directive. If the argument name has not been provided, attempt to deduce it from the schema, or show a warning if not possible
     */
    protected function extractAndValidateFielOrDirectiveArguments(
        string $fieldOrDirective,
        array $fieldOrDirectiveArgElems,
        bool $orderedFieldOrDirectiveArgNamesEnabled,
        array $fieldOrDirectiveArgumentNameTypes,
        array $fieldOrDirectiveArgumentNameDefaultValues,
        ?array $variables,
        array &$schemaErrors,
        array &$schemaWarnings,
        string $resolverType
    ): array {
        if ($orderedFieldOrDirectiveArgNamesEnabled) {
            $orderedFieldOrDirectiveArgNames = array_keys($fieldOrDirectiveArgumentNameTypes);
        }
        $fieldOrDirectiveArgs = [];
        $treatUndefinedFieldOrDirectiveArgsAsErrors = ComponentConfiguration::treatUndefinedFieldOrDirectiveArgsAsErrors();
        $setFailingFieldResponseAsNull = ComponentConfiguration::setFailingFieldResponseAsNull();
        for ($i = 0; $i < count($fieldOrDirectiveArgElems); $i++) {
            $fieldOrDirectiveArg = $fieldOrDirectiveArgElems[$i];
            // Either one of 2 formats are accepted:
            // 1. The key:value pair
            // 2. Only the value, and extract the key from the schema definition (if enabled for that fieldOrDirective)
            $separatorPos = QueryUtils::findFirstSymbolPosition(
                $fieldOrDirectiveArg,
                QuerySyntax::SYMBOL_FIELDARGS_ARGKEYVALUESEPARATOR,
                [QuerySyntax::SYMBOL_FIELDARGS_OPENING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_OPENING],
                [QuerySyntax::SYMBOL_FIELDARGS_CLOSING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_CLOSING],
            );
            if ($separatorPos === false) {
                $fieldOrDirectiveArgValue = $fieldOrDirectiveArg;
                if (!$orderedFieldOrDirectiveArgNamesEnabled || !isset($orderedFieldOrDirectiveArgNames[$i])) {
                    $errorMessage = sprintf(
                        $this->translationAPI->__('The argument on position number %s (with value \'%s\') has its name missing, and %s. Please define the query using the \'key%svalue\' format', 'pop-component-model'),
                        $i + 1,
                        $fieldOrDirectiveArgValue,
                        $orderedFieldOrDirectiveArgNamesEnabled ?
                            $this->translationAPI->__('documentation for this argument in the schema definition has not been defined, hence it can\'t be deduced from there', 'pop-component-model') :
                            $this->translationAPI->__('retrieving this information from the schema definition is disabled for the corresponding “typeResolver”', 'pop-component-model'),
                        QuerySyntax::SYMBOL_FIELDARGS_ARGKEYVALUESEPARATOR
                    );
                    if ($treatUndefinedFieldOrDirectiveArgsAsErrors) {
                        $schemaErrors[] = [
                            Tokens::PATH => [$fieldOrDirective],
                            Tokens::MESSAGE => ($resolverType === ResolverTypes::FIELD || $setFailingFieldResponseAsNull) ?
                                $errorMessage
                                : sprintf(
                                    $this->translationAPI->__('%s. The directive has been ignored', 'pop-component-model'),
                                    $errorMessage
                                ),
                        ];
                    } else {
                        $schemaWarnings[] = [
                            Tokens::PATH => [$fieldOrDirective],
                            Tokens::MESSAGE => sprintf(
                                $this->translationAPI->__('%s. This argument has been ignored', 'pop-component-model'),
                                $errorMessage
                            ),
                        ];
                    }
                    // Ignore extracting this argument
                    continue;
                }
                $fieldOrDirectiveArgName = $orderedFieldOrDirectiveArgNames[$i];
                // Log the found fieldOrDirectiveArgName
                // Must re-cast to this package's class, to avoid IDE showing error
                /** @var FeedbackMessageStoreInterface */
                $feedbackMessageStore = $this->feedbackMessageStore;
                $feedbackMessageStore->maybeAddLogEntry(
                    sprintf(
                        $this->translationAPI->__('In field or directive \'%s\', the argument on position number %s (with value \'%s\') is resolved as argument \'%s\'', 'pop-component-model'),
                        $fieldOrDirective,
                        $i + 1,
                        $fieldOrDirectiveArgValue,
                        $fieldOrDirectiveArgName
                    )
                );
            } else {
                $fieldOrDirectiveArgName = trim(substr($fieldOrDirectiveArg, 0, $separatorPos));
                $fieldOrDirectiveArgValue = trim(substr($fieldOrDirectiveArg, $separatorPos + strlen(QuerySyntax::SYMBOL_FIELDARGS_ARGKEYVALUESEPARATOR)));
                // Validate that this argument exists in the schema, or show a warning if not
                // But don't skip it! It may be that the engine accepts the property, it is just not documented!
                if (!array_key_exists($fieldOrDirectiveArgName, $fieldOrDirectiveArgumentNameTypes)) {
                    $errorMessage = sprintf(
                        $this->translationAPI->__('On %1$s \'%2$s\', argument with name \'%3$s\' has not been documented in the schema', 'pop-component-model'),
                        $resolverType == ResolverTypes::FIELD ? $this->translationAPI->__('field', 'component-model') : $this->translationAPI->__('directive', 'component-model'),
                        $fieldOrDirective,
                        $fieldOrDirectiveArgName
                    );
                    if ($treatUndefinedFieldOrDirectiveArgsAsErrors) {
                        $schemaErrors[] = [
                            Tokens::PATH => [$fieldOrDirective],
                            Tokens::MESSAGE => ($resolverType === ResolverTypes::FIELD || $setFailingFieldResponseAsNull) ?
                                $errorMessage
                                : sprintf(
                                    $this->translationAPI->__('%s. The directive has been ignored', 'pop-component-model'),
                                    $errorMessage
                                ),
                        ];
                    } else {
                        $schemaWarnings[] = [
                            Tokens::PATH => [$fieldOrDirective],
                            Tokens::MESSAGE => sprintf(
                                $this->translationAPI->__('%s, so it may have no effect (it has not been removed from the query, though)', 'pop-component-model'),
                                $errorMessage
                            ),
                        ];
                    }
                }
            }

            // If the field is an array in its string representation, convert it to array
            $fieldOrDirectiveArgValue = $this->maybeConvertFieldArgumentValue($fieldOrDirectiveArgValue, $variables);
            $fieldOrDirectiveArgs[$fieldOrDirectiveArgName] = $fieldOrDirectiveArgValue;
        }

        // Add the entries for all missing fieldArgs with default value
        $fieldOrDirectiveArgs = array_merge(
            $fieldOrDirectiveArgumentNameDefaultValues,
            $fieldOrDirectiveArgs
        );

        return $fieldOrDirectiveArgs;
    }

    public function extractFieldArguments(
        TypeResolverInterface $typeResolver,
        string $field,
        ?array $variables = null,
        ?array &$schemaErrors = null,
        ?array &$schemaWarnings = null,
    ): array {
        $variablesHash = $this->getVariablesHash($variables);
        if (!isset($this->extractedFieldArgumentsCache[get_class($typeResolver)][$field][$variablesHash])) {
            $fieldSchemaErrors = $fieldSchemaWarnings = [];
            $this->extractedFieldArgumentsCache[get_class($typeResolver)][$field][$variablesHash] = $this->doExtractFieldArguments(
                $typeResolver,
                $field,
                $variables,
                $fieldSchemaErrors,
                $fieldSchemaWarnings,
            );
            $this->extractedFieldArgumentErrorsCache[get_class($typeResolver)][$field][$variablesHash] = $fieldSchemaErrors;
            $this->extractedFieldArgumentWarningsCache[get_class($typeResolver)][$field][$variablesHash] = $fieldSchemaWarnings;
        }
        // Integrate the errors/warnings too
        if ($schemaErrors !== null) {
            $schemaErrors = array_merge(
                $schemaErrors,
                $this->extractedFieldArgumentErrorsCache[get_class($typeResolver)][$field][$variablesHash]
            );
        }
        if ($schemaWarnings !== null) {
            $schemaWarnings = array_merge(
                $schemaWarnings,
                $this->extractedFieldArgumentWarningsCache[get_class($typeResolver)][$field][$variablesHash]
            );
        }
        return $this->extractedFieldArgumentsCache[get_class($typeResolver)][$field][$variablesHash];
    }

    protected function doExtractFieldArguments(
        TypeResolverInterface $typeResolver,
        string $field,
        ?array $variables,
        array &$schemaErrors,
        array &$schemaWarnings,
    ): array {
        // Iterate all the elements, and extract them into the array
        $fieldOrDirectiveArgumentNameDefaultValues = $this->getFieldArgumentNameDefaultValues($typeResolver, $field);
        if ($fieldArgElems = QueryHelpers::getFieldArgElements($this->getFieldArgs($field))) {
            $fieldArgumentNameTypes = $this->getFieldArgumentNameTypes($typeResolver, $field);
            $orderedFieldArgNamesEnabled = $typeResolver->enableOrderedSchemaFieldArgs($field);
            return $this->extractAndValidateFielOrDirectiveArguments(
                $field,
                $fieldArgElems,
                $orderedFieldArgNamesEnabled,
                $fieldArgumentNameTypes,
                $fieldOrDirectiveArgumentNameDefaultValues,
                $variables,
                $schemaErrors,
                $schemaWarnings,
                ResolverTypes::FIELD
            );
        }

        return $fieldOrDirectiveArgumentNameDefaultValues;
    }

    protected function filterFieldOrDirectiveArgs(array $fieldOrDirectiveArgs): array
    {
        // Remove all errors, allow null values
        return array_filter(
            $fieldOrDirectiveArgs,
            function ($elem): bool {
                // If the input is `[[String]]`, must then validate if any subitem is Error
                if (is_array($elem)) {
                    // Filter elements in the array. If any is missing, filter the array out
                    $filteredElem = $this->filterFieldOrDirectiveArgs($elem);
                    return count($elem) === count($filteredElem);
                }
                // Remove only Errors. Keep NULL, '', 0, false and []
                return !GeneralUtils::isError($elem);
            }
        );
    }

    public function extractFieldArgumentsForSchema(
        TypeResolverInterface $typeResolver,
        string $field,
        ?array $variables = null
    ): array {
        $schemaErrors = [];
        $schemaWarnings = [];
        $schemaDeprecations = [];
        $validAndResolvedField = $field;
        $fieldName = $this->getFieldName($field);
        $extractedFieldArgs = $fieldArgs = $this->extractFieldArguments(
            $typeResolver,
            $field,
            $variables,
            $schemaErrors,
            $schemaWarnings,
        );
        $fieldArgs = $this->validateExtractedFieldOrDirectiveArgumentsForSchema($typeResolver, $field, $fieldArgs, $variables, $schemaErrors, $schemaWarnings, $schemaDeprecations);
        // Cast the values to their appropriate type. If casting fails, the value returns as null
        $fieldArgs = $this->castAndValidateFieldArgumentsForSchema($typeResolver, $field, $fieldArgs, $schemaErrors, $schemaWarnings);
        // Enable the typeResolver to add its own code validations
        $fieldArgs = $typeResolver->validateFieldArgumentsForSchema($field, $fieldArgs, $schemaErrors, $schemaWarnings, $schemaDeprecations);

        // If there's an error, those args will be removed. Then, re-create the fieldDirective to pass it to the function below
        if ($schemaErrors) {
            $validAndResolvedField = null;
        } elseif ($extractedFieldArgs != $fieldArgs) {
            // There are 2 reasons why the field might have changed:
            // 1. validField: There are $schemaWarnings: remove the fieldArgs that failed
            // 2. resolvedField: Some fieldArg was a variable: replace it with its value
            $validAndResolvedField = $this->replaceFieldArgs($field, $fieldArgs);
        }
        return [
            $validAndResolvedField,
            $fieldName,
            $fieldArgs,
            $schemaErrors,
            $schemaWarnings,
            $schemaDeprecations,
        ];
    }

    public function extractDirectiveArgumentsForSchema(
        DirectiveResolverInterface $directiveResolver,
        TypeResolverInterface $typeResolver,
        string $fieldDirective,
        ?array $variables = null,
        bool $disableDynamicFields = false
    ): array {
        $schemaErrors = [];
        $schemaWarnings = [];
        $schemaDeprecations = [];
        $validAndResolvedDirective = $fieldDirective;
        $directiveName = $this->getFieldDirectiveName($fieldDirective);
        $extractedDirectiveArgs = $directiveArgs = $this->extractDirectiveArguments(
            $directiveResolver,
            $typeResolver,
            $fieldDirective,
            $variables,
            $schemaErrors,
            $schemaWarnings,
        );
        $directiveArgs = $this->validateExtractedFieldOrDirectiveArgumentsForSchema($typeResolver, $fieldDirective, $directiveArgs, $variables, $schemaErrors, $schemaWarnings, $schemaDeprecations);
        // Cast the values to their appropriate type. If casting fails, the value returns as null
        $directiveArgs = $this->castAndValidateDirectiveArgumentsForSchema($directiveResolver, $typeResolver, $fieldDirective, $directiveArgs, $schemaErrors, $schemaWarnings, $disableDynamicFields);
        // Enable the directiveResolver to add its own code validations
        $directiveArgs = $directiveResolver->validateDirectiveArgumentsForSchema($typeResolver, $directiveName, $directiveArgs, $schemaErrors, $schemaWarnings, $schemaDeprecations);

        // If there's an error, those args will be removed. Then, re-create the fieldDirective to pass it to the function below
        if ($schemaErrors) {
            $validAndResolvedDirective = null;
        } elseif ($extractedDirectiveArgs != $directiveArgs) {
            // There are 2 reasons why the fieldDirective might have changed:
            // 1. validField: There are $schemaWarnings: remove the directiveArgs that failed
            // 2. resolvedField: Some directiveArg was a variable: replace it with its value
            $validAndResolvedDirective = $this->replaceFieldArgs($fieldDirective, $directiveArgs);
        }
        return [
            $validAndResolvedDirective,
            $directiveName,
            $directiveArgs,
            $schemaErrors,
            $schemaWarnings,
            $schemaDeprecations,
        ];
    }

    /**
     * Add the field or directive to the head of the error path, for all nested errors
     */
    protected function prependPathOnNestedErrors(array &$nestedFieldOrDirectiveSchemaError, string $fieldOrDirective): void
    {

        if (isset($nestedFieldOrDirectiveSchemaError[Tokens::EXTENSIONS][Tokens::NESTED])) {
            foreach ($nestedFieldOrDirectiveSchemaError[Tokens::EXTENSIONS][Tokens::NESTED] as &$deeplyNestedFieldOrDirectiveSchemaError) {
                array_unshift($deeplyNestedFieldOrDirectiveSchemaError[Tokens::PATH], $fieldOrDirective);
                $this->prependPathOnNestedErrors($deeplyNestedFieldOrDirectiveSchemaError, $fieldOrDirective);
            }
        }
    }

    protected function validateExtractedFieldOrDirectiveArgumentsForSchema(TypeResolverInterface $typeResolver, string $fieldOrDirective, array $fieldOrDirectiveArgs, ?array $variables, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations): array
    {
        if ($fieldOrDirectiveArgs) {
            foreach ($fieldOrDirectiveArgs as $argName => $argValue) {
                // Validate it
                if ($maybeErrors = $this->resolveFieldArgumentValueErrorDescriptionsForSchema($typeResolver, $argValue, $variables)) {
                    foreach ($maybeErrors as $schemaError) {
                        array_unshift($schemaError[Tokens::PATH], $fieldOrDirective);
                        $this->prependPathOnNestedErrors($schemaError, $fieldOrDirective);
                        $schemaErrors[] = $schemaError;
                    }
                    // Because it's an error, set the value to null, so it will be filtered out
                    $fieldOrDirectiveArgs[$argName] = null;
                }
                // Find warnings and deprecations
                if ($maybeWarnings = $this->resolveFieldArgumentValueWarningsForSchema($typeResolver, $argValue, $variables)) {
                    foreach ($maybeWarnings as $schemaWarning) {
                        array_unshift($schemaWarning[Tokens::PATH], $fieldOrDirective);
                        $schemaWarnings[] = $schemaWarning;
                    }
                }
                if ($maybeDeprecations = $this->resolveFieldArgumentValueDeprecationsForSchema($typeResolver, $argValue, $variables)) {
                    foreach ($maybeDeprecations as $schemaDeprecation) {
                        array_unshift($schemaDeprecation[Tokens::PATH], $fieldOrDirective);
                        $schemaDeprecations[] = $schemaDeprecation;
                    }
                }
            }
            // If there was an error, remove those entries
            $fieldOrDirectiveArgs = $this->filterFieldOrDirectiveArgs($fieldOrDirectiveArgs);
        }
        return $fieldOrDirectiveArgs;
    }

    public function extractFieldArgumentsForResultItem(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $field,
        ?array $variables,
        ?array $expressions
    ): array {
        $dbErrors = $dbWarnings = [];
        $validAndResolvedField = $field;
        $fieldName = $this->getFieldName($field);
        $extractedFieldArgs = $fieldArgs = $this->extractFieldArguments(
            $typeResolver,
            $field,
            $variables
        );
        // Only need to extract arguments if they have fields or arrays
        $fieldOutputKey = $this->getFieldOutputKey($field);
        $fieldArgs = $this->extractFieldOrDirectiveArgumentsForResultItem($typeResolver, $resultItem, $fieldArgs, $fieldOutputKey, $variables, $expressions, $dbErrors);
        // Cast the values to their appropriate type. If casting fails, the value returns as null
        $resultItemDBErrors = $resultItemDBWarnings = [];
        $fieldArgs = $this->castAndValidateFieldArgumentsForResultItem($typeResolver, $field, $fieldArgs, $resultItemDBErrors, $resultItemDBWarnings);
        if ($resultItemDBErrors || $resultItemDBWarnings) {
            $id = $typeResolver->getID($resultItem);
            if ($resultItemDBErrors) {
                $dbErrors[(string)$id] = array_merge(
                    $dbErrors[(string)$id] ?? [],
                    $resultItemDBErrors
                );
            }
            if ($resultItemDBWarnings) {
                $dbWarnings[(string)$id] = array_merge(
                    $dbWarnings[(string)$id] ?? [],
                    $resultItemDBWarnings
                );
            }
        }
        if ($dbErrors) {
            $validAndResolvedField = null;
        } elseif ($extractedFieldArgs != $fieldArgs) {
            // There are 2 reasons why the field might have changed:
            // 1. validField: There are $dbWarnings: remove the fieldArgs that failed
            // 2. resolvedField: Some fieldArg was a variable: replace it with its value
            $validAndResolvedField = $this->replaceFieldArgs($field, $fieldArgs);
        }
        return [
            $validAndResolvedField,
            $fieldName,
            $fieldArgs,
            $dbErrors,
            $dbWarnings
        ];
    }

    public function extractDirectiveArgumentsForResultItem(
        DirectiveResolverInterface $directiveResolver,
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldDirective,
        array $variables,
        array $expressions
    ): array {
        $dbErrors = $dbWarnings = [];
        $validAndResolvedDirective = $fieldDirective;
        $directiveName = $this->getFieldDirectiveName($fieldDirective);
        $extractedDirectiveArgs = $directiveArgs = $this->extractDirectiveArguments(
            $directiveResolver,
            $typeResolver,
            $fieldDirective,
            $variables,
        );
        // Only need to extract arguments if they have fields or arrays
        $directiveOutputKey = $this->getDirectiveOutputKey($fieldDirective);
        $directiveArgs = $this->extractFieldOrDirectiveArgumentsForResultItem($typeResolver, $resultItem, $directiveArgs, $directiveOutputKey, $variables, $expressions, $dbErrors);
        // Cast the values to their appropriate type. If casting fails, the value returns as null
        $resultItemDBErrors = $resultItemDBWarnings = [];
        $directiveArgs = $this->castAndValidateDirectiveArgumentsForResultItem($directiveResolver, $typeResolver, $fieldDirective, $directiveArgs, $resultItemDBErrors, $resultItemDBWarnings);
        if ($resultItemDBErrors || $resultItemDBWarnings) {
            $id = $typeResolver->getID($resultItem);
            if ($resultItemDBErrors) {
                $dbErrors[(string)$id] = array_merge(
                    $dbErrors[(string)$id] ?? [],
                    $resultItemDBErrors
                );
            }
            if ($resultItemDBWarnings) {
                $dbWarnings[(string)$id] = array_merge(
                    $dbWarnings[(string)$id] ?? [],
                    $resultItemDBWarnings
                );
            }
        }
        if ($dbErrors) {
            $validAndResolvedDirective = null;
        } elseif ($extractedDirectiveArgs != $directiveArgs) {
            // There are 2 reasons why the fieldDirective might have changed:
            // 1. validField: There are $dbWarnings: remove the directiveArgs that failed
            // 2. resolvedField: Some directiveArg was a variable: replace it with its value
            $validAndResolvedDirective = $this->replaceFieldArgs($fieldDirective, $directiveArgs);
        }
        return [
            $validAndResolvedDirective,
            $directiveName,
            $directiveArgs,
            $dbErrors,
            $dbWarnings
        ];
    }

    protected function extractFieldOrDirectiveArgumentsForResultItem(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        array $fieldOrDirectiveArgs,
        string $fieldOrDirectiveOutputKey,
        ?array $variables,
        ?array $expressions,
        array &$dbErrors
    ): array {
        // Only need to extract arguments if they have fields or arrays
        if (
            FieldQueryUtils::isAnyFieldArgumentValueDynamic(
                array_values(
                    $fieldOrDirectiveArgs
                )
            )
        ) {
            $id = $typeResolver->getID($resultItem);
            foreach ($fieldOrDirectiveArgs as $directiveArgName => $directiveArgValue) {
                $directiveArgValue = $this->maybeResolveFieldArgumentValueForResultItem($typeResolver, $resultItem, $directiveArgValue, $variables, $expressions);
                // Validate it
                if (GeneralUtils::isError($directiveArgValue)) {
                    /** @var Error */
                    $error = $directiveArgValue;
                    if ($errorData = $error->getData()) {
                        $errorFieldOrDirective = $errorData[ErrorDataTokens::FIELD_NAME] ?? null;
                    }
                    $errorFieldOrDirective = $errorFieldOrDirective ?? $fieldOrDirectiveOutputKey;
                    $dbErrors[(string)$id][] = [
                        Tokens::PATH => [$errorFieldOrDirective],
                        Tokens::MESSAGE => $error->getMessageOrCode(),
                    ];
                    $fieldOrDirectiveArgs[$directiveArgName] = null;
                    continue;
                }
                $fieldOrDirectiveArgs[$directiveArgName] = $directiveArgValue;
            }
            return $this->filterFieldOrDirectiveArgs($fieldOrDirectiveArgs);
        }
        return $fieldOrDirectiveArgs;
    }

    protected function castDirectiveArguments(
        DirectiveResolverInterface $directiveResolver,
        TypeResolverInterface $typeResolver,
        string $directive,
        array $directiveArgs,
        array &$failedCastingDirectiveArgErrorMessages,
        bool $forSchema
    ): array {
        $directiveArgSchemaDefinition = $this->getDirectiveSchemaDefinitionArgs($directiveResolver, $typeResolver);
        return $this->castFieldOrDirectiveArguments(
            $directiveArgs,
            $directiveArgSchemaDefinition,
            $failedCastingDirectiveArgErrorMessages,
            $forSchema
        );
    }

    protected function castFieldArguments(
        TypeResolverInterface $typeResolver,
        string $field,
        array $fieldArgs,
        array &$failedCastingFieldArgErrorMessages,
        bool $forSchema
    ): array {
        $fieldArgSchemaDefinition = $this->getFieldSchemaDefinitionArgs($typeResolver, $field);
        return $this->castFieldOrDirectiveArguments(
            $fieldArgs,
            $fieldArgSchemaDefinition,
            $failedCastingFieldArgErrorMessages,
            $forSchema
        );
    }

    protected function castFieldOrDirectiveArguments(
        array $fieldOrDirectiveArgs,
        array $fieldOrDirectiveArgSchemaDefinition,
        array &$failedCastingFieldOrDirectiveArgErrorMessages,
        bool $forSchema
    ): array {
        // Cast all argument values
        foreach ($fieldOrDirectiveArgs as $argName => $argValue) {
            // There are 2 possibilities for casting:
            // 1. $forSchema = true: Cast all items except fields (eg: hasComments()) or arrays with fields (eg: [hasComments()])
            // 2. $forSchema = false: Should be cast only fields, however by now we can't tell which are fields and which are not, since fields have already been resolved to their value. Hence, cast everything (fieldArgValues that failed at the schema level will not be provided in the input array, so won't be validated twice)
            // Otherwise, simply add the argValue directly, it will be eventually casted by the other function
            if (
                !$forSchema
                // Conditions below are for `$forSchema => true`
                || (!is_array($argValue) && !$this->isFieldArgumentValueDynamic($argValue))
                || (is_array($argValue) && !FieldQueryUtils::isAnyFieldArgumentValueDynamic($argValue))
            ) {
                /**
                 * Maybe cast the value to the appropriate type.
                 * Eg: from string to boolean.
                 *
                 * Handle also the case of executing a query with a fieldArg
                 * that was not defined in the schema. Eg:
                 *
                 * ```
                 * { posts(thisArgIsNonExistent: "saloro") { id } }
                 * ```
                 *
                 * In that case, assign type `MIXED`, which implies "Do not cast"
                 **/
                $fieldOrDirectiveArgType = $fieldOrDirectiveArgSchemaDefinition[$argName][SchemaDefinition::ARGNAME_TYPE] ?? SchemaDefinition::TYPE_MIXED;
                // If not set, the return type is not an array
                $fieldOrDirectiveArgIsArrayType = $fieldOrDirectiveArgSchemaDefinition[$argName][SchemaDefinition::ARGNAME_IS_ARRAY] ?? false;
                $fieldOrDirectiveArgIsNonNullArrayItemsType = $fieldOrDirectiveArgSchemaDefinition[$argName][SchemaDefinition::ARGNAME_IS_NON_NULLABLE_ITEMS_IN_ARRAY] ?? false;
                $fieldOrDirectiveArgIsArrayOfArraysType = $fieldOrDirectiveArgSchemaDefinition[$argName][SchemaDefinition::ARGNAME_IS_ARRAY_OF_ARRAYS] ?? false;
                $fieldOrDirectiveArgIsNonNullArrayOfArraysItemsType = $fieldOrDirectiveArgSchemaDefinition[$argName][SchemaDefinition::ARGNAME_IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS] ?? false;

                /**
                 * This value will not be used with GraphQL, but can be used by PoP.
                 *
                 * While GraphQL has a strong type system, PoP takes a more lenient approach,
                 * enabling fields to maybe be an array, maybe not.
                 *
                 * Eg: `echo(value: ...)` will print back whatever provided,
                 * whether `String` or `[String]`. Its input is `Mixed`, which can comprise
                 * an `Object`, so it could be provided as an array, or also `String`, which
                 * will not be an array.
                 *
                 * Whenever the value may be an array, the server will skip those validations
                 * to check if an input is array or not (and throw an error).
                 */
                $fieldOrDirectiveArgMayBeArrayType = in_array($fieldOrDirectiveArgType, [
                    SchemaDefinition::TYPE_INPUT_OBJECT,
                    SchemaDefinition::TYPE_OBJECT,
                    SchemaDefinition::TYPE_MIXED,
                ]);
                if (!$fieldOrDirectiveArgMayBeArrayType) {
                    /**
                     * Support passing a single value where a list is expected:
                     * `{ posts(ids: 1) }` means `{ posts(ids: [1]) }`
                     *
                     * Defined in the GraphQL spec.
                     *
                     * @see https://spec.graphql.org/draft/#sec-List.Input-Coercion
                     */
                    if (
                        !is_array($argValue)
                        && ComponentConfiguration::coerceInputFromSingleValueToList()
                    ) {
                        if ($fieldOrDirectiveArgIsArrayOfArraysType) {
                            $argValue = [[$argValue]];
                        } elseif ($fieldOrDirectiveArgIsArrayType) {
                            $argValue = [$argValue];
                        }
                    }

                    // Validate that the expected array/non-array input is provided
                    $errorMessage = null;
                    if (
                        !$fieldOrDirectiveArgIsArrayType
                        && is_array($argValue)
                    ) {
                        $errorMessage = sprintf(
                            $this->translationAPI->__('Argument \'%s\' does not expect an array, but array \'%s\' was provided', 'pop-component-model'),
                            $argName,
                            json_encode($argValue)
                        );
                    } elseif (
                        $fieldOrDirectiveArgIsArrayType
                        && !is_array($argValue)
                    ) {
                        $errorMessage = sprintf(
                            $this->translationAPI->__('Argument \'%s\' expects an array, but value \'%s\' was provided', 'pop-component-model'),
                            $argName,
                            $argValue
                        );
                    } elseif (
                        $fieldOrDirectiveArgIsNonNullArrayItemsType
                        && is_array($argValue)
                        && array_filter(
                            $argValue,
                            fn ($arrayItem) => $arrayItem === null
                        )
                    ) {
                        $errorMessage = sprintf(
                            $this->translationAPI->__('Argument \'%s\' cannot receive an array with `null` values', 'pop-component-model'),
                            $argName
                        );
                    } elseif (
                        $fieldOrDirectiveArgIsArrayType
                        && !$fieldOrDirectiveArgIsArrayOfArraysType
                        && array_filter(
                            $argValue,
                            fn ($arrayItem) => is_array($arrayItem)
                        )
                    ) {
                        $errorMessage = sprintf(
                            $this->translationAPI->__('Argument \'%s\' cannot receive an array containing arrays as elements', 'pop-component-model'),
                            $argName,
                            json_encode($argValue)
                        );
                    } elseif (
                        $fieldOrDirectiveArgIsArrayOfArraysType
                        && is_array($argValue)
                        && array_filter(
                            $argValue,
                            // `null` could be accepted as an array! (Validation against null comes next)
                            fn ($arrayItem) => !is_array($arrayItem) && $arrayItem !== null
                        )
                    ) {
                        $errorMessage = sprintf(
                            $this->translationAPI->__('Argument \'%s\' expects an array of arrays, but value \'%s\' was provided', 'pop-component-model'),
                            $argName,
                            json_encode($argValue)
                        );
                    } elseif (
                        $fieldOrDirectiveArgIsNonNullArrayOfArraysItemsType
                        && is_array($argValue)
                        && array_filter(
                            $argValue,
                            fn (?array $arrayItem) => $arrayItem === null ? false : array_filter(
                                $arrayItem,
                                fn ($arrayItemItem) => $arrayItemItem === null
                            ) !== [],
                        )
                    ) {
                        $errorMessage = sprintf(
                            $this->translationAPI->__('Argument \'%s\' cannot receive an array of arrays with `null` values', 'pop-component-model'),
                            $argName
                        );
                    }

                    if ($errorMessage !== null) {
                        $failedCastingFieldOrDirectiveArgErrorMessages[$argName] = $errorMessage;
                        $fieldOrDirectiveArgs[$argName] = null;
                        continue;
                    }
                }

                /** @var Error[] */
                $errorArgValues = [];
                // Cast (or "coerce" in GraphQL terms) the value
                if ($fieldOrDirectiveArgIsArrayOfArraysType) {
                    // If the value is an array of arrays, then cast each subelement to the item type
                    $argValue = array_map(
                        // If it contains a null value, return it as is
                        fn (?array $arrayArgValueElem) => $arrayArgValueElem === null ? null : array_map(
                            fn (mixed $arrayOfArraysArgValueElem) => $arrayOfArraysArgValueElem === null ? null : $this->typeCastingExecuter->cast(
                                $fieldOrDirectiveArgType,
                                $arrayOfArraysArgValueElem
                            ),
                            $arrayArgValueElem
                        ),
                        $argValue
                    );
                    $errorArgValues = GeneralUtils::arrayFlatten(array_filter(
                        $argValue,
                        fn (?array $arrayArgValueElem) => $arrayArgValueElem === null ? false : array_filter(
                            $arrayArgValueElem,
                            fn (mixed $arrayOfArraysArgValueElem) => GeneralUtils::isError($arrayOfArraysArgValueElem)
                        )
                    ));
                } elseif ($fieldOrDirectiveArgIsArrayType) {
                    // If the value is an array, then cast each element to the item type
                    $argValue = array_map(
                        fn (mixed $arrayArgValueElem) => $arrayArgValueElem === null ? null : $this->typeCastingExecuter->cast(
                            $fieldOrDirectiveArgType,
                            $arrayArgValueElem
                        ),
                        $argValue
                    );
                    $errorArgValues = array_filter(
                        $argValue,
                        fn (mixed $arrayArgValueElem) => GeneralUtils::isError($arrayArgValueElem)
                    );
                } else {
                    // Otherwise, simply cast the given value directly
                    $argValue = $argValue === null ? null : $this->typeCastingExecuter->cast($fieldOrDirectiveArgType, $argValue);
                    if (GeneralUtils::isError($argValue)) {
                        /** @var Error $argValue */
                        $errorArgValues[] = $argValue;
                    }
                }

                // If the response is an error, extract the error message and set value to null
                if ($errorArgValues) {
                    $castingErrorMessage = count($errorArgValues) === 1 ?
                        $errorArgValues[0]->getMessageOrCode()
                        : implode(
                            $this->translationAPI->__('; ', 'pop-component-model'),
                            array_map(
                                fn (Error $errorArgValueElem) => $errorArgValueElem->getMessageOrCode(),
                                $errorArgValues
                            )
                        );
                    $failedCastingFieldOrDirectiveArgErrorMessages[$argName] = $castingErrorMessage;
                    $fieldOrDirectiveArgs[$argName] = null;
                    continue;
                }
                $fieldOrDirectiveArgs[$argName] = $argValue;
            }
        }
        return $fieldOrDirectiveArgs;
    }

    protected function castDirectiveArgumentsForSchema(DirectiveResolverInterface $directiveResolver, TypeResolverInterface $typeResolver, string $fieldDirective, array $directiveArgs, array &$failedCastingDirectiveArgErrorMessages, bool $disableDynamicFields = false): array
    {
        // If the directive doesn't allow dynamic fields (Eg: <cacheControl(maxAge:id())>), then treat it as not for schema
        $forSchema = !$disableDynamicFields;
        return $this->castDirectiveArguments($directiveResolver, $typeResolver, $fieldDirective, $directiveArgs, $failedCastingDirectiveArgErrorMessages, $forSchema);
    }

    protected function castFieldArgumentsForSchema(TypeResolverInterface $typeResolver, string $field, array $fieldArgs, array &$failedCastingFieldArgErrorMessages): array
    {
        return $this->castFieldArguments($typeResolver, $field, $fieldArgs, $failedCastingFieldArgErrorMessages, true);
    }

    protected function castDirectiveArgumentsForResultItem(DirectiveResolverInterface $directiveResolver, TypeResolverInterface $typeResolver, string $directive, array $directiveArgs, array &$failedCastingDirectiveArgErrorMessages): array
    {
        return $this->castDirectiveArguments($directiveResolver, $typeResolver, $directive, $directiveArgs, $failedCastingDirectiveArgErrorMessages, false);
    }

    protected function castFieldArgumentsForResultItem(TypeResolverInterface $typeResolver, string $field, array $fieldArgs, array &$failedCastingFieldArgErrorMessages): array
    {
        return $this->castFieldArguments($typeResolver, $field, $fieldArgs, $failedCastingFieldArgErrorMessages, false);
    }

    protected function getDirectiveArgumentNameTypes(DirectiveResolverInterface $directiveResolver, TypeResolverInterface $typeResolver): array
    {
        if (!isset($this->directiveArgumentNameTypesCache[get_class($directiveResolver)][get_class($typeResolver)])) {
            $this->directiveArgumentNameTypesCache[get_class($directiveResolver)][get_class($typeResolver)] = $this->doGetDirectiveArgumentNameTypes($directiveResolver, $typeResolver);
        }
        return $this->directiveArgumentNameTypesCache[get_class($directiveResolver)][get_class($typeResolver)];
    }

    protected function doGetDirectiveArgumentNameTypes(DirectiveResolverInterface $directiveResolver, TypeResolverInterface $typeResolver): array
    {
        // Get the fieldDirective argument types, to know to what type it will cast the value
        $directiveArgNameTypes = [];
        if ($directiveSchemaDefinitionArgs = $this->getDirectiveSchemaDefinitionArgs($directiveResolver, $typeResolver)) {
            foreach ($directiveSchemaDefinitionArgs as $directiveSchemaDefinitionArg) {
                $directiveArgNameTypes[$directiveSchemaDefinitionArg[SchemaDefinition::ARGNAME_NAME]] = $directiveSchemaDefinitionArg[SchemaDefinition::ARGNAME_TYPE];
            }
        }
        return $directiveArgNameTypes;
    }

    protected function getDirectiveArgumentNameDefaultValues(DirectiveResolverInterface $directiveResolver, TypeResolverInterface $typeResolver): array
    {
        if (!isset($this->directiveArgumentNameDefaultValuesCache[get_class($directiveResolver)][get_class($typeResolver)])) {
            $this->directiveArgumentNameDefaultValuesCache[get_class($directiveResolver)][get_class($typeResolver)] = $this->doGetDirectiveArgumentNameDefaultValues($directiveResolver, $typeResolver);
        }
        return $this->directiveArgumentNameDefaultValuesCache[get_class($directiveResolver)][get_class($typeResolver)];
    }

    protected function doGetDirectiveArgumentNameDefaultValues(DirectiveResolverInterface $directiveResolver, TypeResolverInterface $typeResolver): array
    {
        // Get the directive arguments which have a default value
        $directiveArgNameDefaultValues = [];
        if ($directiveSchemaDefinitionArgs = $this->getDirectiveSchemaDefinitionArgs($directiveResolver, $typeResolver)) {
            $directiveSchemaDefinitionArgsWithDefaultValue = array_filter(
                $directiveSchemaDefinitionArgs,
                function (array $directiveSchemaDefinitionArg): bool {
                    return \array_key_exists(SchemaDefinition::ARGNAME_DEFAULT_VALUE, $directiveSchemaDefinitionArg);
                }
            );
            foreach ($directiveSchemaDefinitionArgsWithDefaultValue as $directiveSchemaDefinitionArg) {
                $directiveArgNameDefaultValues[$directiveSchemaDefinitionArg[SchemaDefinition::ARGNAME_NAME]] = $directiveSchemaDefinitionArg[SchemaDefinition::ARGNAME_DEFAULT_VALUE];
            }
        }
        return $directiveArgNameDefaultValues;
    }

    protected function getFieldSchemaDefinitionArgs(TypeResolverInterface $typeResolver, string $field): array
    {
        if (!isset($this->fieldSchemaDefinitionArgsCache[get_class($typeResolver)][$field])) {
            $this->fieldSchemaDefinitionArgsCache[get_class($typeResolver)][$field] = $typeResolver->getSchemaFieldArgs($field);
        }
        return $this->fieldSchemaDefinitionArgsCache[get_class($typeResolver)][$field];
    }

    protected function getDirectiveSchemaDefinitionArgs(DirectiveResolverInterface $directiveResolver, TypeResolverInterface $typeResolver): array
    {
        if (!isset($this->directiveSchemaDefinitionArgsCache[get_class($directiveResolver)][get_class($typeResolver)])) {
            $directiveSchemaDefinition = $directiveResolver->getSchemaDefinitionForDirective($typeResolver);
            $directiveSchemaDefinitionArgs = $directiveSchemaDefinition[SchemaDefinition::ARGNAME_ARGS] ?? [];
            $this->directiveSchemaDefinitionArgsCache[get_class($directiveResolver)][get_class($typeResolver)] = $directiveSchemaDefinitionArgs;
        }
        return $this->directiveSchemaDefinitionArgsCache[get_class($directiveResolver)][get_class($typeResolver)];
    }

    protected function getFieldArgumentNameTypes(TypeResolverInterface $typeResolver, string $field): array
    {
        if (!isset($this->fieldArgumentNameTypesCache[get_class($typeResolver)][$field])) {
            $this->fieldArgumentNameTypesCache[get_class($typeResolver)][$field] = $this->doGetFieldArgumentNameTypes($typeResolver, $field);
        }
        return $this->fieldArgumentNameTypesCache[get_class($typeResolver)][$field];
    }

    protected function doGetFieldArgumentNameTypes(TypeResolverInterface $typeResolver, string $field): array
    {
        // Get the field argument types, to know to what type it will cast the value
        $fieldArgNameTypes = [];
        if ($fieldSchemaDefinitionArgs = $this->getFieldSchemaDefinitionArgs($typeResolver, $field)) {
            foreach ($fieldSchemaDefinitionArgs as $fieldSchemaDefinitionArg) {
                $fieldArgNameTypes[$fieldSchemaDefinitionArg[SchemaDefinition::ARGNAME_NAME]] = $fieldSchemaDefinitionArg[SchemaDefinition::ARGNAME_TYPE];
            }
        }
        return $fieldArgNameTypes;
    }

    protected function getFieldArgumentNameDefaultValues(TypeResolverInterface $typeResolver, string $field): array
    {
        if (!isset($this->fieldArgumentNameDefaultValuesCache[get_class($typeResolver)][$field])) {
            $this->fieldArgumentNameDefaultValuesCache[get_class($typeResolver)][$field] = $this->doGetFieldArgumentNameDefaultValues($typeResolver, $field);
        }
        return $this->fieldArgumentNameDefaultValuesCache[get_class($typeResolver)][$field];
    }

    protected function doGetFieldArgumentNameDefaultValues(TypeResolverInterface $typeResolver, string $field): array
    {
        // Get the field arguments which have a default value
        $fieldArgNameDefaultValues = [];
        if ($fieldSchemaDefinitionArgs = $this->getFieldSchemaDefinitionArgs($typeResolver, $field)) {
            $fieldSchemaDefinitionArgsWithDefaultValue = array_filter(
                $fieldSchemaDefinitionArgs,
                function (array $fieldSchemaDefinitionArg): bool {
                    return \array_key_exists(SchemaDefinition::ARGNAME_DEFAULT_VALUE, $fieldSchemaDefinitionArg);
                }
            );
            foreach ($fieldSchemaDefinitionArgsWithDefaultValue as $fieldSchemaDefinitionArg) {
                $fieldArgNameDefaultValues[$fieldSchemaDefinitionArg[SchemaDefinition::ARGNAME_NAME]] = $fieldSchemaDefinitionArg[SchemaDefinition::ARGNAME_DEFAULT_VALUE];
            }
        }
        return $fieldArgNameDefaultValues;
    }

    protected function castAndValidateDirectiveArgumentsForSchema(DirectiveResolverInterface $directiveResolver, TypeResolverInterface $typeResolver, string $fieldDirective, array $directiveArgs, array &$schemaErrors, array &$schemaWarnings, bool $disableDynamicFields = false): array
    {
        if ($directiveArgs) {
            $failedCastingDirectiveArgErrorMessages = [];
            $castedDirectiveArgs = $this->castDirectiveArgumentsForSchema($directiveResolver, $typeResolver, $fieldDirective, $directiveArgs, $failedCastingDirectiveArgErrorMessages, $disableDynamicFields);
            return $this->castAndValidateDirectiveArguments($directiveResolver, $typeResolver, $castedDirectiveArgs, $failedCastingDirectiveArgErrorMessages, $fieldDirective, $directiveArgs, $schemaErrors, $schemaWarnings);
        }
        return $directiveArgs;
    }

    protected function castAndValidateFieldArgumentsForSchema(TypeResolverInterface $typeResolver, string $field, array $fieldArgs, array &$schemaErrors, array &$schemaWarnings): array
    {
        if ($fieldArgs) {
            $failedCastingFieldArgErrorMessages = [];
            $castedFieldArgs = $this->castFieldArgumentsForSchema($typeResolver, $field, $fieldArgs, $failedCastingFieldArgErrorMessages);
            return $this->castAndValidateFieldArguments($typeResolver, $castedFieldArgs, $failedCastingFieldArgErrorMessages, $field, $fieldArgs, $schemaErrors, $schemaWarnings);
        }
        return $fieldArgs;
    }

    protected function castAndValidateDirectiveArgumentsForResultItem(DirectiveResolverInterface $directiveResolver, TypeResolverInterface $typeResolver, string $fieldDirective, array $directiveArgs, array &$dbErrors, array &$dbWarnings): array
    {
        $failedCastingDirectiveArgErrorMessages = [];
        $castedDirectiveArgs = $this->castDirectiveArgumentsForResultItem($directiveResolver, $typeResolver, $fieldDirective, $directiveArgs, $failedCastingDirectiveArgErrorMessages);
        return $this->castAndValidateDirectiveArguments($directiveResolver, $typeResolver, $castedDirectiveArgs, $failedCastingDirectiveArgErrorMessages, $fieldDirective, $directiveArgs, $dbErrors, $dbWarnings);
    }

    protected function castAndValidateFieldArgumentsForResultItem(TypeResolverInterface $typeResolver, string $field, array $fieldArgs, array &$dbErrors, array &$dbWarnings): array
    {
        $failedCastingFieldArgErrorMessages = [];
        $castedFieldArgs = $this->castFieldArgumentsForResultItem($typeResolver, $field, $fieldArgs, $failedCastingFieldArgErrorMessages);
        return $this->castAndValidateFieldArguments($typeResolver, $castedFieldArgs, $failedCastingFieldArgErrorMessages, $field, $fieldArgs, $dbErrors, $dbWarnings);
    }

    protected function castAndValidateDirectiveArguments(DirectiveResolverInterface $directiveResolver, TypeResolverInterface $typeResolver, array $castedDirectiveArgs, array &$failedCastingDirectiveArgErrorMessages, string $fieldDirective, array $directiveArgs, array &$schemaErrors, array &$schemaWarnings): array
    {
        // If any casting can't be done, show an error
        if (
            $failedCastingDirectiveArgs = array_filter($castedDirectiveArgs, function ($directiveArgValue) {
                return $directiveArgValue === null;
            })
        ) {
            $directiveName = $this->getFieldDirectiveName($fieldDirective);
            $directiveArgNameTypes = $this->getDirectiveArgumentNameTypes($directiveResolver, $typeResolver);
            $directiveArgNameSchemaDefinition = $this->getDirectiveSchemaDefinitionArgs($directiveResolver, $typeResolver);
            $treatTypeCoercingFailuresAsErrors = ComponentConfiguration::treatTypeCoercingFailuresAsErrors();
            foreach (array_keys($failedCastingDirectiveArgs) as $failedCastingDirectiveArgName) {
                // If it is Error, also show the error message
                $directiveArgIsArrayType = $directiveArgNameSchemaDefinition[$failedCastingDirectiveArgName][SchemaDefinition::ARGNAME_IS_ARRAY] ?? false;
                $directiveArgIsArrayOfArraysType = $directiveArgNameSchemaDefinition[$failedCastingDirectiveArgName][SchemaDefinition::ARGNAME_IS_ARRAY_OF_ARRAYS] ?? false;
                $composedDirectiveArgType = $directiveArgNameTypes[$failedCastingDirectiveArgName];
                if ($directiveArgIsArrayOfArraysType) {
                    $composedDirectiveArgType = sprintf(
                        $this->translationAPI->__('array of arrays of %s', 'pop-component-model'),
                        $composedDirectiveArgType
                    );
                } elseif ($directiveArgIsArrayType) {
                    $composedDirectiveArgType = sprintf(
                        $this->translationAPI->__('array of %s', 'pop-component-model'),
                        $composedDirectiveArgType
                    );
                }
                if ($directiveArgErrorMessage = $failedCastingDirectiveArgErrorMessages[$failedCastingDirectiveArgName] ?? null) {
                    $errorMessage = sprintf(
                        $this->translationAPI->__('For directive \'%s\', casting value \'%s\' for argument \'%s\' to type \'%s\' failed: %s', 'pop-component-model'),
                        $directiveName,
                        is_array($directiveArgs[$failedCastingDirectiveArgName]) ? json_encode($directiveArgs[$failedCastingDirectiveArgName]) : $directiveArgs[$failedCastingDirectiveArgName],
                        $failedCastingDirectiveArgName,
                        $composedDirectiveArgType,
                        $directiveArgErrorMessage
                    );
                } else {
                    $errorMessage = sprintf(
                        $this->translationAPI->__('For directive \'%s\', casting value \'%s\' for argument \'%s\' to type \'%s\' failed', 'pop-component-model'),
                        $directiveName,
                        is_array($directiveArgs[$failedCastingDirectiveArgName]) ? json_encode($directiveArgs[$failedCastingDirectiveArgName]) : $directiveArgs[$failedCastingDirectiveArgName],
                        $failedCastingDirectiveArgName,
                        $composedDirectiveArgType
                    );
                }
                // Either treat it as an error or a warning
                if ($treatTypeCoercingFailuresAsErrors) {
                    $schemaErrors[] = [
                        Tokens::PATH => [$fieldDirective],
                        Tokens::MESSAGE => $errorMessage,
                    ];
                } else {
                    $errorMessage = sprintf(
                        $this->translationAPI->__('%1$s. It has been ignored', 'pop-component-model'),
                        $errorMessage
                    );
                    $schemaWarnings[] = [
                        Tokens::PATH => [$fieldDirective],
                        Tokens::MESSAGE => $errorMessage,
                    ];
                }
            }
            return $this->filterFieldOrDirectiveArgs($castedDirectiveArgs);
        }
        return $castedDirectiveArgs;
    }

    /**
     * Any element that is Error, or any array that contains an error
     */
    protected function getFailedCastingFieldArgs(array $castedFieldArgs): array
    {
        return array_filter(
            $castedFieldArgs,
            fn (mixed $fieldArgValue) =>
                (!is_array($fieldArgValue) && GeneralUtils::isError($fieldArgValue))
                || (is_array($fieldArgValue) && !empty($this->getFailedCastingFieldArgs($fieldArgValue)))
        );
    }

    protected function castAndValidateFieldArguments(
        TypeResolverInterface $typeResolver,
        array $castedFieldArgs,
        array &$failedCastingFieldArgErrorMessages,
        string $field,
        array $fieldArgs,
        array &$schemaErrors,
        array &$schemaWarnings
    ): array {
        // If any casting can't be done, show an error
        if ($failedCastingFieldArgs = $this->getFailedCastingFieldArgs($castedFieldArgs)) {
            $fieldName = $this->getFieldName($field);
            $fieldArgNameTypes = $this->getFieldArgumentNameTypes($typeResolver, $field);
            $fieldArgNameSchemaDefinition = $this->getFieldSchemaDefinitionArgs($typeResolver, $field);
            $treatTypeCoercingFailuresAsErrors = ComponentConfiguration::treatTypeCoercingFailuresAsErrors();
            foreach (array_keys($failedCastingFieldArgs) as $failedCastingFieldArgName) {
                // If it is Error, also show the error message
                $fieldArgIsArrayType = $fieldArgNameSchemaDefinition[$failedCastingFieldArgName][SchemaDefinition::ARGNAME_IS_ARRAY] ?? false;
                $fieldArgIsArrayOfArraysType = $fieldArgNameSchemaDefinition[$failedCastingFieldArgName][SchemaDefinition::ARGNAME_IS_ARRAY_OF_ARRAYS] ?? false;
                $composedFieldArgType = $fieldArgNameTypes[$failedCastingFieldArgName];
                if ($fieldArgIsArrayOfArraysType) {
                    $composedFieldArgType = sprintf(
                        $this->translationAPI->__('array of arrays of %s', 'pop-component-model'),
                        $composedFieldArgType
                    );
                } elseif ($fieldArgIsArrayType) {
                    $composedFieldArgType = sprintf(
                        $this->translationAPI->__('array of %s', 'pop-component-model'),
                        $composedFieldArgType
                    );
                }
                if ($fieldArgErrorMessage = $failedCastingFieldArgErrorMessages[$failedCastingFieldArgName] ?? null) {
                    $errorMessage = sprintf(
                        $this->translationAPI->__('For field \'%s\', casting value \'%s\' for argument \'%s\' to type \'%s\' failed: %s', 'pop-component-model'),
                        $fieldName,
                        is_array($fieldArgs[$failedCastingFieldArgName]) ? json_encode($fieldArgs[$failedCastingFieldArgName]) : $fieldArgs[$failedCastingFieldArgName],
                        $failedCastingFieldArgName,
                        $composedFieldArgType,
                        $fieldArgErrorMessage
                    );
                } else {
                    $errorMessage = sprintf(
                        $this->translationAPI->__('For field \'%s\', casting value \'%s\' for argument \'%s\' to type \'%s\' failed', 'pop-component-model'),
                        $fieldName,
                        is_array($fieldArgs[$failedCastingFieldArgName]) ? json_encode($fieldArgs[$failedCastingFieldArgName]) : $fieldArgs[$failedCastingFieldArgName],
                        $failedCastingFieldArgName,
                        $composedFieldArgType
                    );
                }
                if ($treatTypeCoercingFailuresAsErrors) {
                    $schemaErrors[] = [
                        Tokens::PATH => [$field],
                        Tokens::MESSAGE => $errorMessage,
                    ];
                } else {
                    $errorMessage = sprintf(
                        $this->translationAPI->__('%1$s. It has been ignored', 'pop-component-model'),
                        $errorMessage
                    );
                    $schemaWarnings[] = [
                        Tokens::PATH => [$field],
                        Tokens::MESSAGE => $errorMessage,
                    ];
                }
            }
            return $this->filterFieldOrDirectiveArgs($castedFieldArgs);
        }
        return $castedFieldArgs;
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
                    $fieldArgValue = $this->maybeConvertFieldArgumentArrayValue($fieldArgValue, $variables);
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
            // If not passed the variables parameter, use $_REQUEST["variables"] by default
            if (is_null($variables)) {
                $vars = ApplicationState::getVars();
                $variables = $vars['variables'];
            }
            $variableName = substr($fieldArgValue, strlen(QuerySyntax::SYMBOL_VARIABLE_PREFIX));
            if (isset($variables[$variableName])) {
                return $variables[$variableName];
            }
            // If the variable is not set, then show the error under entry "variableErrors"
            $this->feedbackMessageStore->addQueryError(sprintf(
                $this->translationAPI->__('Variable \'%s\' is undefined', 'pop-component-model'),
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
            $fieldArgValueElems = $this->queryParser->splitElements($arrayValue, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_SEPARATOR, [QuerySyntax::SYMBOL_FIELDARGS_OPENING, QuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_OPENING], [QuerySyntax::SYMBOL_FIELDARGS_CLOSING, QuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_CLOSING], QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING);
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
                $fieldArgValueElemComponents = $this->queryParser->splitElements($fieldArgValueElem, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_KEYVALUEDELIMITER, [QuerySyntax::SYMBOL_FIELDARGS_OPENING, QuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_OPENING], [QuerySyntax::SYMBOL_FIELDARGS_CLOSING, QuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_CLOSING], QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING);
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

    public function maybeConvertFieldArgumentArrayValue(mixed $fieldArgValue, ?array $variables = null): mixed
    {
        if (is_string($fieldArgValue)) {
            $fieldArgValue = $this->maybeConvertFieldArgumentArrayValueFromStringToArray($fieldArgValue);
        }
        if (is_array($fieldArgValue)) {
            // Resolve each element the same way
            return $this->filterFieldOrDirectiveArgs(
                array_map(function ($arrayValueElem) use ($variables) {
                    return $this->maybeConvertFieldArgumentValue($arrayValueElem, $variables);
                }, $fieldArgValue)
            );
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
    protected function maybeResolveFieldArgumentValueForResultItem(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        mixed $fieldArgValue,
        ?array $variables,
        ?array $expressions
    ): mixed {
        // If it is an array, apply this function on all elements
        if (is_array($fieldArgValue)) {
            return array_map(
                function ($fieldArgValueElem) use ($typeResolver, $resultItem, $variables, $expressions) {
                    return $this->maybeResolveFieldArgumentValueForResultItem($typeResolver, $resultItem, $fieldArgValueElem, $variables, $expressions);
                },
                (array)$fieldArgValue
            );
        }

        // Execute as expression
        if ($this->isFieldArgumentValueAnExpression($fieldArgValue)) {
            // Expressions: allow to pass a field argument "key:%input%", which is passed when executing the directive through $expressions
            // Trim it so that "% self %" is equivalent to "%self%". This is needed to set expressions through Symfony's DependencyInjection component (since %...% is reserved for its own parameters!)
            $expressionName = trim(substr($fieldArgValue, strlen(QuerySyntax::SYMBOL_EXPRESSION_OPENING), strlen($fieldArgValue) - strlen(QuerySyntax::SYMBOL_EXPRESSION_OPENING) - strlen(QuerySyntax::SYMBOL_EXPRESSION_CLOSING)));
            if (isset($expressions[$expressionName])) {
                return $expressions[$expressionName];
            }
            // If the expression is not set, then show the error under entry "expressionErrors"
            $this->feedbackMessageStore->addQueryError(sprintf(
                $this->translationAPI->__('Expression \'%s\' is undefined', 'pop-component-model'),
                $expressionName
            ));
            return null;
        } elseif ($this->isFieldArgumentValueAField($fieldArgValue)) {
            // Execute as field
            // It is important to force the validation, because if a needed argument is provided with an error, it needs to be validated, casted and filtered out,
            // and if this wrong param is not "dynamic", then the validation would not take place
            $options = [
                AbstractTypeResolver::OPTION_VALIDATE_SCHEMA_ON_RESULT_ITEM => true,
            ];
            $resolvedValue = $typeResolver->resolveValue($resultItem, (string)$fieldArgValue, $variables, $expressions, $options);
            if (GeneralUtils::isError($resolvedValue)) {
                // Show the error message, and return nothing
                /** @var Error */
                $error = $resolvedValue;
                $this->feedbackMessageStore->addQueryError(sprintf(
                    $this->translationAPI->__('Executing field \'%s\' produced error: %s', 'pop-component-model'),
                    $fieldArgValue,
                    $error->getMessageOrCode()
                ));
                return null;
            }
            return $resolvedValue;
        }

        return $fieldArgValue;
    }

    protected function resolveFieldArgumentValueErrorDescriptionsForSchema(TypeResolverInterface $typeResolver, mixed $fieldArgValue, ?array $variables): array
    {
        // If it is an array, apply this function on all elements
        if (is_array($fieldArgValue)) {
            return GeneralUtils::arrayFlatten(array_filter(array_map(function ($fieldArgValueElem) use ($typeResolver, $variables) {
                return $this->resolveFieldArgumentValueErrorDescriptionsForSchema($typeResolver, $fieldArgValueElem, $variables);
            }, $fieldArgValue)));
        }

        // If the result fieldArgValue is a string (i.e. not numeric), and it has brackets (...),
        // and is not wrapped with quotes, then it is a field. Validate it and resolve it
        if (!empty($fieldArgValue) && is_string($fieldArgValue) && !is_numeric($fieldArgValue) && !$this->isFieldArgumentValueWrappedWithStringSymbols((string) $fieldArgValue)) {
            $fieldArgValue = (string)$fieldArgValue;
            // If it has the fieldArg brackets, and the last bracket is at the end, then it's a field!
            list(
                $fieldArgsOpeningSymbolPos,
                $fieldArgsClosingSymbolPos
            ) = QueryHelpers::listFieldArgsSymbolPositions($fieldArgValue);

            // If there is no "(" or ")", or if the ")" is not at the end, of if the "(" is at the beginning, then it's simply a string
            if ($fieldArgsClosingSymbolPos !== (strlen($fieldArgValue) - strlen(QuerySyntax::SYMBOL_FIELDARGS_CLOSING)) || $fieldArgsOpeningSymbolPos === false || $fieldArgsOpeningSymbolPos === 0) {
                return [];
            }
            // // If there is only one of them, it's a query error, so discard the query bit
            // if (($fieldArgsClosingSymbolPos === false && $fieldArgsOpeningSymbolPos !== false) || ($fieldArgsClosingSymbolPos !== false && $fieldArgsOpeningSymbolPos === false)) {
            //     return [
            //         sprintf(
            //             $this->translationAPI->__('Arguments in field \'%s\' must start with symbol \'%s\' and end with symbol \'%s\', so they have been ignored', 'pop-component-model'),
            //             $fieldArgValue,
            //             QuerySyntax::SYMBOL_FIELDARGS_OPENING,
            //             QuerySyntax::SYMBOL_FIELDARGS_CLOSING
            //         ),
            //     ];
            // }

            // // If the opening bracket is at the beginning, or the closing one is not at the end, it's an error
            // if ($fieldArgsOpeningSymbolPos === 0) {
            //     return [
            //         sprintf(
            //             $this->translationAPI->__('Field name is missing in \'%s\', so it has been ignored', 'pop-component-model'),
            //             $fieldArgValue
            //         ),
            //     ];
            // }
            // if ($fieldArgsClosingSymbolPos !== strlen($fieldArgValue)-1) {
            //     return [
            //         sprintf(
            //             $this->translationAPI->__('Field \'%s\' has arguments, but because the closing argument symbol \'%s\' is not at the end, it has been ignored', 'pop-component-model'),
            //             $fieldArgValue,
            //             QuerySyntax::SYMBOL_FIELDARGS_CLOSING
            //         ),
            //     ];
            // }

            // If it reached here, it's a field! Validate it, or show an error
            return $typeResolver->resolveSchemaValidationErrorDescriptions($fieldArgValue, $variables);
        }

        return [];
    }

    protected function resolveFieldArgumentValueWarningsForSchema(TypeResolverInterface $typeResolver, mixed $fieldArgValue, ?array $variables): array
    {
        // If it is an array, apply this function on all elements
        if (is_array($fieldArgValue)) {
            return GeneralUtils::arrayFlatten(array_filter(array_map(function ($fieldArgValueElem) use ($typeResolver, $variables) {
                return $this->resolveFieldArgumentValueWarningsForSchema($typeResolver, $fieldArgValueElem, $variables);
            }, $fieldArgValue)));
        }

        // If the result fieldArgValue is a field, then validate it and resolve it
        if ($this->isFieldArgumentValueAField($fieldArgValue)) {
            return $typeResolver->resolveSchemaValidationWarningDescriptions($fieldArgValue, $variables);
        }

        return [];
    }

    protected function resolveFieldArgumentValueDeprecationsForSchema(TypeResolverInterface $typeResolver, mixed $fieldArgValue, ?array $variables): array
    {
        // If it is an array, apply this function on all elements
        if (is_array($fieldArgValue)) {
            return GeneralUtils::arrayFlatten(array_filter(array_map(function ($fieldArgValueElem) use ($typeResolver, $variables) {
                return $this->resolveFieldArgumentValueDeprecationsForSchema($typeResolver, $fieldArgValueElem, $variables);
            }, $fieldArgValue)));
        }

        // If the result fieldArgValue is a field, then validate it and resolve it
        if ($this->isFieldArgumentValueAField($fieldArgValue)) {
            return $typeResolver->resolveSchemaDeprecationDescriptions($fieldArgValue, $variables);
        }

        return [];
    }

    protected function getNoAliasFieldOutputKey(string $field): string
    {
        // GraphQL: Use fieldName only
        $vars = ApplicationState::getVars();
        if ($vars['only-fieldname-as-outputkey'] ?? null) {
            return $this->getFieldName($field);
        }
        return parent::getNoAliasFieldOutputKey($field);
    }
}
