<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\Root\App;
use Exception;
use PoP\ComponentModel\Component;
use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Error\Error;
use PoP\ComponentModel\Error\ErrorDataTokens;
use PoP\ComponentModel\Error\ErrorServiceInterface;
use PoP\ComponentModel\Feedback\Tokens;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\ObjectSerialization\ObjectSerializationManagerInterface;
use PoP\ComponentModel\Resolvers\ResolverTypes;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\TypeResolvers\AbstractRelationalTypeResolver;
use PoP\ComponentModel\TypeResolvers\DeprecatableInputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\DangerouslyDynamicScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoP\FieldQuery\FieldQueryInterpreter as UpstreamFieldQueryInterpreter;
use PoP\FieldQuery\QueryHelpers;
use PoP\FieldQuery\QuerySyntax;
use PoP\FieldQuery\QueryUtils;
use stdClass;

class FieldQueryInterpreter extends UpstreamFieldQueryInterpreter implements FieldQueryInterpreterInterface
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

    /**
     * @var array<string,array<string,string>>
     */
    private array $fieldOutputKeysByTypeAndField = [];
    /**
     * @var array<string,array<string,string>>
     */
    private array $fieldsByTypeAndFieldOutputKey = [];

    private ?DangerouslyDynamicScalarTypeResolver $dangerouslyDynamicScalarTypeResolver = null;
    private ?ErrorServiceInterface $errorService = null;
    private ?InputCoercingServiceInterface $inputCoercingService = null;
    private ?ObjectSerializationManagerInterface $objectSerializationManager = null;

    final public function setDangerouslyDynamicScalarTypeResolver(DangerouslyDynamicScalarTypeResolver $dangerouslyDynamicScalarTypeResolver): void
    {
        $this->dangerouslyDynamicScalarTypeResolver = $dangerouslyDynamicScalarTypeResolver;
    }
    final protected function getDangerouslyDynamicScalarTypeResolver(): DangerouslyDynamicScalarTypeResolver
    {
        return $this->dangerouslyDynamicScalarTypeResolver ??= $this->instanceManager->getInstance(DangerouslyDynamicScalarTypeResolver::class);
    }
    final public function setErrorService(ErrorServiceInterface $errorService): void
    {
        $this->errorService = $errorService;
    }
    final protected function getErrorService(): ErrorServiceInterface
    {
        return $this->errorService ??= $this->instanceManager->getInstance(ErrorServiceInterface::class);
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

    /**
     * If two different fields for the same type have the same fieldOutputKey, then
     * add a counter to the second one, so each of them is unique.
     * That is to avoid overriding the previous value, as when doing:
     *
     *   ?query=posts.title|self.excerpt@title
     *
     * In this case, the value of the excerpt would override the value of the title,
     * since they both have fieldOutputKey "title".
     *
     * If the TypeResolver is of Union type, because the data for the object
     * is stored under the target ObjectTypeResolver, then the unique field name
     * must be retrieved against the target ObjectTypeResolver
     */
    final public function getUniqueFieldOutputKey(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $field,
        object $object,
    ): string {
        if ($relationalTypeResolver instanceof UnionTypeResolverInterface) {
            $targetObjectTypeResolver = $relationalTypeResolver->getTargetObjectTypeResolver($object);
            if ($targetObjectTypeResolver === null) {
                throw new Exception(
                    sprintf(
                        $this->__('The Union Type \'%s\' does not provide a target ObjectTypeResolver for the object', 'component-model'),
                        $relationalTypeResolver->getMaybeNamespacedTypeName()
                    )
                );
            }
            return $this->getUniqueFieldOutputKeyByObjectTypeResolver(
                $targetObjectTypeResolver,
                $field
            );
        }
        return $this->getUniqueFieldOutputKeyByTypeOutputDBKey(
            $relationalTypeResolver->getTypeOutputDBKey(),
            $field
        );
    }

    /**
     * If the TypeResolver is of Union type, and we don't have the object
     * (eg: when printing the configuration), then generate a list of the
     * unique field outputs for all the target ObjectTypeResolvers.
     *
     * If the TypeResolver is an Object type, to respect the same response,
     * return an array of a single element, with its own unique field output.
     *
     * @return array<string,string>
     */
    final public function getTargetObjectTypeUniqueFieldOutputKeys(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $field,
    ): array {
        $uniqueFieldOutputKeys = [];
        /** @var ObjectTypeResolverInterface[] */
        $targetObjectTypeResolvers = $relationalTypeResolver instanceof UnionTypeResolverInterface ?
            $relationalTypeResolver->getTargetObjectTypeResolvers()
            : [$relationalTypeResolver];

        foreach ($targetObjectTypeResolvers as $targetObjectTypeResolver) {
            $uniqueFieldOutputKeys[$targetObjectTypeResolver->getTypeName()] = $this->getUniqueFieldOutputKeyByObjectTypeResolver(
                $targetObjectTypeResolver,
                $field
            );
        }
        return $uniqueFieldOutputKeys;
    }

    final public function getUniqueFieldOutputKeyByObjectTypeResolver(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $field,
    ): string {
        return $this->getUniqueFieldOutputKeyByTypeOutputDBKey(
            $objectTypeResolver->getTypeOutputDBKey(),
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
    public function getUniqueFieldOutputKeyByTypeOutputDBKey(string $typeOutputDBKey, string $field): string
    {
        /**
         * Watch out! The conditional field symbol `?` must be ignored!
         * Otherwise the same field, with and without ?, will be considered different,
         * but they are the same:
         *
         * - the field without "?" is used to resolve the field
         * - the field with "?" is used to retrieve the value to print in the response
         *
         * Eg:
         *   /?query=post(id:1).id|title
         */
        $field = $this->removeSkipOuputIfNullFromField($field);
        // If a fieldOutputKey has already been created for this field, retrieve it
        if ($fieldOutputKey = $this->fieldOutputKeysByTypeAndField[$typeOutputDBKey][$field] ?? null) {
            return $fieldOutputKey;
        }
        $fieldOutputKey = $this->getFieldOutputKey($field);
        if (!isset($this->fieldsByTypeAndFieldOutputKey[$typeOutputDBKey][$fieldOutputKey])) {
            $this->fieldsByTypeAndFieldOutputKey[$typeOutputDBKey][$fieldOutputKey] = $field;
            $this->fieldOutputKeysByTypeAndField[$typeOutputDBKey][$field] = $fieldOutputKey;
            return $fieldOutputKey;
        }
        // This fieldOutputKey already exists for a different field,
        // then create a counter and iterate until it doesn't exist anymore
        $counter = 0;
        while (isset($this->fieldsByTypeAndFieldOutputKey[$typeOutputDBKey][$fieldOutputKey . '-' . $counter])) {
            $counter++;
        }
        $fieldOutputKey = $fieldOutputKey . '-' . $counter;
        $this->fieldsByTypeAndFieldOutputKey[$typeOutputDBKey][$fieldOutputKey] = $field;
        $this->fieldOutputKeysByTypeAndField[$typeOutputDBKey][$field] = $fieldOutputKey;
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
                if ($fieldArgElems = $this->getQueryParser()->splitElements($fieldArgsStr, QuerySyntax::SYMBOL_FIELDARGS_ARGSEPARATOR, [QuerySyntax::SYMBOL_FIELDARGS_OPENING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_OPENING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_OPENING], [QuerySyntax::SYMBOL_FIELDARGS_CLOSING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_CLOSING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_CLOSING], QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING)) {
                    for ($i = 0; $i < count($fieldArgElems); $i++) {
                        $fieldArg = $fieldArgElems[$i];
                        // If there is no separator, then skip this arg, since it is not static (without the schema, we can't know which fieldArgName it is)
                        $separatorPos = QueryUtils::findFirstSymbolPosition(
                            $fieldArg,
                            QuerySyntax::SYMBOL_FIELDARGS_ARGKEYVALUESEPARATOR,
                            [QuerySyntax::SYMBOL_FIELDARGS_OPENING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_OPENING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_OPENING],
                            [QuerySyntax::SYMBOL_FIELDARGS_CLOSING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_CLOSING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_CLOSING],
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
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $fieldDirective,
        ?array $variables = null,
        ?array &$schemaErrors = null,
        ?array &$schemaWarnings = null,
    ): array {
        $variablesHash = $this->getVariablesHash($variables);
        $relationalTypeResolverClass = get_class($relationalTypeResolver);
        if (!isset($this->extractedDirectiveArgumentsCache[$relationalTypeResolverClass][$fieldDirective][$variablesHash])) {
            $fieldSchemaWarnings = $fieldSchemaErrors = [];
            $this->extractedDirectiveArgumentsCache[$relationalTypeResolverClass][$fieldDirective][$variablesHash] = $this->doExtractDirectiveArguments(
                $directiveResolver,
                $relationalTypeResolver,
                $fieldDirective,
                $variables,
                $fieldSchemaErrors,
                $fieldSchemaWarnings,
            );
            $this->extractedDirectiveArgumentErrorsCache[$relationalTypeResolverClass][$fieldDirective][$variablesHash] = $fieldSchemaErrors;
            $this->extractedDirectiveArgumentWarningsCache[$relationalTypeResolverClass][$fieldDirective][$variablesHash] = $fieldSchemaWarnings;
        }
        // Integrate the errors/warnings too
        if ($schemaErrors !== null) {
            $schemaErrors = array_merge(
                $schemaErrors,
                $this->extractedDirectiveArgumentErrorsCache[$relationalTypeResolverClass][$fieldDirective][$variablesHash]
            );
        }
        if ($schemaWarnings !== null) {
            $schemaWarnings = array_merge(
                $schemaWarnings,
                $this->extractedDirectiveArgumentWarningsCache[$relationalTypeResolverClass][$fieldDirective][$variablesHash]
            );
        }
        return $this->extractedDirectiveArgumentsCache[$relationalTypeResolverClass][$fieldDirective][$variablesHash];
    }

    protected function doExtractDirectiveArguments(
        DirectiveResolverInterface $directiveResolver,
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $fieldDirective,
        ?array $variables,
        array &$schemaErrors,
        array &$schemaWarnings,
    ): array {
        $directiveArgumentNameDefaultValues = $this->getDirectiveArgumentNameDefaultValues($directiveResolver, $relationalTypeResolver);
        // Iterate all the elements, and extract them into the array
        if ($directiveArgElems = QueryHelpers::getFieldArgElements($this->getFieldDirectiveArgs($fieldDirective))) {
            $directiveArgumentNameTypeResolvers = $this->getDirectiveArgumentNameTypeResolvers($directiveResolver, $relationalTypeResolver);
            $orderedDirectiveArgNamesEnabled = $directiveResolver->enableOrderedSchemaDirectiveArgs($relationalTypeResolver);
            return $this->extractAndValidateFielOrDirectiveArguments(
                $fieldDirective,
                $directiveArgElems,
                $orderedDirectiveArgNamesEnabled,
                $directiveArgumentNameTypeResolvers,
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
     * Extract the arguments for either the field or directive.
     * If the argument name has not been provided,
     * attempt to deduce it from the schema,
     * or show a warning if not possible
     *
     * @param array<string, InputTypeResolverInterface> $fieldOrDirectiveArgumentNameTypeResolvers
     */
    protected function extractAndValidateFielOrDirectiveArguments(
        string $fieldOrDirective,
        array $fieldOrDirectiveArgElems,
        bool $orderedFieldOrDirectiveArgNamesEnabled,
        array $fieldOrDirectiveArgumentNameTypeResolvers,
        array $fieldOrDirectiveArgumentNameDefaultValues,
        ?array $variables,
        array &$schemaErrors,
        array &$schemaWarnings,
        string $resolverType
    ): array {
        if ($orderedFieldOrDirectiveArgNamesEnabled) {
            $orderedFieldOrDirectiveArgNames = array_keys($fieldOrDirectiveArgumentNameTypeResolvers);
        }
        $fieldOrDirectiveArgs = [];
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        $treatUndefinedFieldOrDirectiveArgsAsErrors = $componentConfiguration->treatUndefinedFieldOrDirectiveArgsAsErrors();
        $setFailingFieldResponseAsNull = $componentConfiguration->setFailingFieldResponseAsNull();
        for ($i = 0; $i < count($fieldOrDirectiveArgElems); $i++) {
            $fieldOrDirectiveArg = $fieldOrDirectiveArgElems[$i];
            // Either one of 2 formats are accepted:
            // 1. The key:value pair
            // 2. Only the value, and extract the key from the schema definition (if enabled for that fieldOrDirective)
            $separatorPos = QueryUtils::findFirstSymbolPosition(
                $fieldOrDirectiveArg,
                QuerySyntax::SYMBOL_FIELDARGS_ARGKEYVALUESEPARATOR,
                [QuerySyntax::SYMBOL_FIELDARGS_OPENING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_OPENING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_OPENING],
                [QuerySyntax::SYMBOL_FIELDARGS_CLOSING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_CLOSING, QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEOBJECT_CLOSING],
            );
            if ($separatorPos === false) {
                $fieldOrDirectiveArgValue = $fieldOrDirectiveArg;
                if (!$orderedFieldOrDirectiveArgNamesEnabled || !isset($orderedFieldOrDirectiveArgNames[$i])) {
                    $errorMessage = sprintf(
                        $this->__('The argument on position number %s (with value \'%s\') has its name missing, and %s. Please define the query using the \'key%svalue\' format', 'pop-component-model'),
                        $i + 1,
                        $fieldOrDirectiveArgValue,
                        $orderedFieldOrDirectiveArgNamesEnabled ?
                            $this->__('documentation for this argument in the schema definition has not been defined, hence it can\'t be deduced from there', 'pop-component-model') :
                            $this->__('retrieving this information from the schema definition is not enabled for the field', 'pop-component-model'),
                        QuerySyntax::SYMBOL_FIELDARGS_ARGKEYVALUESEPARATOR
                    );
                    if ($treatUndefinedFieldOrDirectiveArgsAsErrors) {
                        $schemaErrors[] = [
                            Tokens::PATH => [$fieldOrDirective],
                            Tokens::MESSAGE => ($resolverType === ResolverTypes::FIELD || $setFailingFieldResponseAsNull) ?
                                $errorMessage
                                : sprintf(
                                    $this->__('%s. The directive has been ignored', 'pop-component-model'),
                                    $errorMessage
                                ),
                        ];
                    } else {
                        $schemaWarnings[] = [
                            Tokens::PATH => [$fieldOrDirective],
                            Tokens::MESSAGE => sprintf(
                                $this->__('%s. This argument has been ignored', 'pop-component-model'),
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
                $feedbackMessageStore = $this->getFeedbackMessageStore();
                $feedbackMessageStore->maybeAddLogEntry(
                    sprintf(
                        $this->__('In field or directive \'%s\', the argument on position number %s (with value \'%s\') is resolved as argument \'%s\'', 'pop-component-model'),
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
                if (!array_key_exists($fieldOrDirectiveArgName, $fieldOrDirectiveArgumentNameTypeResolvers)) {
                    $errorMessage = sprintf(
                        $this->__('On %1$s \'%2$s\', there is no argument with name \'%3$s\'', 'pop-component-model'),
                        $resolverType == ResolverTypes::FIELD ? $this->__('field', 'component-model') : $this->__('directive', 'component-model'),
                        $this->getFieldName($fieldOrDirective),
                        $fieldOrDirectiveArgName
                    );
                    if ($treatUndefinedFieldOrDirectiveArgsAsErrors) {
                        $schemaErrors[] = [
                            Tokens::PATH => [$fieldOrDirective],
                            Tokens::MESSAGE => ($resolverType === ResolverTypes::FIELD || $setFailingFieldResponseAsNull) ?
                                $errorMessage
                                : sprintf(
                                    $this->__('%s. The directive has been ignored', 'pop-component-model'),
                                    $errorMessage
                                ),
                                Tokens::EXTENSIONS => [
                                    Tokens::ARGUMENT_PATH => [$fieldOrDirectiveArgName],
                                ],
                        ];
                        continue;
                    } else {
                        $schemaWarnings[] = [
                            Tokens::PATH => [$fieldOrDirective],
                            Tokens::MESSAGE => sprintf(
                                $this->__('%s, so it may have no effect (it has not been removed from the query, though)', 'pop-component-model'),
                                $errorMessage
                            ),
                            Tokens::EXTENSIONS => [
                                Tokens::ARGUMENT_PATH => [$fieldOrDirectiveArgName],
                            ],
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

    /**
     * Return `null` if there is no resolver for the field
     */
    public function extractFieldArguments(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $field,
        ?array $variables = null,
        ?array &$schemaErrors = null,
        ?array &$schemaWarnings = null,
    ): ?array {
        $variablesHash = $this->getVariablesHash($variables);
        $objectTypeResolverClass = get_class($objectTypeResolver);
        if (!array_key_exists($variablesHash, $this->extractedFieldArgumentsCache[$objectTypeResolverClass][$field] ?? [])) {
            $fieldSchemaErrors = $fieldSchemaWarnings = [];
            $this->extractedFieldArgumentsCache[$objectTypeResolverClass][$field][$variablesHash] = $this->doExtractFieldArguments(
                $objectTypeResolver,
                $field,
                $variables,
                $fieldSchemaErrors,
                $fieldSchemaWarnings,
            );
            $this->extractedFieldArgumentErrorsCache[$objectTypeResolverClass][$field][$variablesHash] = $fieldSchemaErrors;
            $this->extractedFieldArgumentWarningsCache[$objectTypeResolverClass][$field][$variablesHash] = $fieldSchemaWarnings;
        }
        // Integrate the errors/warnings too
        if ($schemaErrors !== null) {
            $schemaErrors = array_merge(
                $schemaErrors,
                $this->extractedFieldArgumentErrorsCache[$objectTypeResolverClass][$field][$variablesHash]
            );
        }
        if ($schemaWarnings !== null) {
            $schemaWarnings = array_merge(
                $schemaWarnings,
                $this->extractedFieldArgumentWarningsCache[$objectTypeResolverClass][$field][$variablesHash]
            );
        }
        return $this->extractedFieldArgumentsCache[$objectTypeResolverClass][$field][$variablesHash];
    }

    protected function doExtractFieldArguments(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $field,
        ?array $variables,
        array &$schemaErrors,
        array &$schemaWarnings,
    ): ?array {
        // Iterate all the elements, and extract them into the array
        $fieldArgumentNameTypeResolvers = $this->getFieldArgumentNameTypeResolvers($objectTypeResolver, $field);
        if ($fieldArgumentNameTypeResolvers === null) {
            $schemaErrors[] = [
                Tokens::PATH => [$field],
                Tokens::MESSAGE => $this->getNoFieldErrorMessage($objectTypeResolver, $field),
            ];
            return null;
        }
        /** @var array */
        $fieldOrDirectiveArgumentNameDefaultValues = $this->getFieldArgumentNameDefaultValues($objectTypeResolver, $field);
        if ($fieldArgElems = QueryHelpers::getFieldArgElements($this->getFieldArgs($field))) {
            /** @var array */
            $fieldSchemaDefinition = $objectTypeResolver->getFieldSchemaDefinition($field);
            $orderedFieldArgsEnabled = $fieldSchemaDefinition[SchemaDefinition::ORDERED_ARGS_ENABLED] ?? false;
            return $this->extractAndValidateFielOrDirectiveArguments(
                $field,
                $fieldArgElems,
                $orderedFieldArgsEnabled,
                $fieldArgumentNameTypeResolvers,
                $fieldOrDirectiveArgumentNameDefaultValues,
                $variables,
                $schemaErrors,
                $schemaWarnings,
                ResolverTypes::FIELD
            );
        }

        return $fieldOrDirectiveArgumentNameDefaultValues;
    }

    protected function getNoFieldErrorMessage(ObjectTypeResolverInterface $objectTypeResolver, string $field): string
    {
        return sprintf(
            $this->__('There is no field \'%s\' on type \'%s\'', 'component-model'),
            $this->getFieldName($field),
            $objectTypeResolver->getMaybeNamespacedTypeName()
        );
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
        ObjectTypeResolverInterface $objectTypeResolver,
        string $field,
        ?array $variables = null
    ): array {
        $schemaErrors = [];
        $schemaWarnings = [];
        $schemaDeprecations = [];
        $validAndResolvedField = $field;
        $fieldName = $this->getFieldName($field);
        $extractedFieldArgs = $fieldArgs = $this->extractFieldArguments(
            $objectTypeResolver,
            $field,
            $variables,
            $schemaErrors,
            $schemaWarnings,
        );
        // If there is no resolver for the field, we will already have an error by now
        if ($schemaErrors) {
            return [
                null,
                $fieldName,
                $fieldArgs ?? [],
                $schemaErrors,
                $schemaWarnings,
                $schemaDeprecations,
            ];
        }
        $fieldArgs = $this->validateExtractedFieldOrDirectiveArgumentsForSchema($objectTypeResolver, $field, $fieldArgs, $variables, $schemaErrors, $schemaWarnings, $schemaDeprecations);
        // Cast the values to their appropriate type. If casting fails, the value returns as null
        $fieldArgs = $this->castAndValidateFieldArgumentsForSchema($objectTypeResolver, $field, $fieldArgs, $schemaErrors, $schemaWarnings, $schemaDeprecations);

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

    public function extractDirectiveArgumentsForSchema(
        DirectiveResolverInterface $directiveResolver,
        RelationalTypeResolverInterface $relationalTypeResolver,
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
            $relationalTypeResolver,
            $fieldDirective,
            $variables,
            $schemaErrors,
            $schemaWarnings,
        );
        $directiveArgs = $this->validateExtractedFieldOrDirectiveArgumentsForSchema($relationalTypeResolver, $fieldDirective, $directiveArgs, $variables, $schemaErrors, $schemaWarnings, $schemaDeprecations);
        // Cast the values to their appropriate type. If casting fails, the value returns as null
        $directiveArgs = $this->castAndValidateDirectiveArgumentsForSchema($directiveResolver, $relationalTypeResolver, $fieldDirective, $directiveArgs, $schemaErrors, $schemaWarnings, $schemaDeprecations, $disableDynamicFields);
        // Enable the directiveResolver to add its own code validations
        $directiveArgs = $directiveResolver->validateDirectiveArgumentsForSchema($relationalTypeResolver, $directiveName, $directiveArgs, $schemaErrors, $schemaWarnings, $schemaDeprecations);

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

    protected function validateExtractedFieldOrDirectiveArgumentsForSchema(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldOrDirective, array $fieldOrDirectiveArgs, ?array $variables, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations): array
    {
        // The UnionTypeResolver cannot validate, it needs to delegate to each targetObjectTypeResolver
        // which will be done on Object, not on Schema.
        // This situation can only happen for Directive
        // (which can receive RelationalType), not for Field (which receives ObjectType)
        if ($relationalTypeResolver instanceof UnionTypeResolverInterface) {
            return $fieldOrDirectiveArgs;
        }
        /** @var ObjectTypeResolverInterface */
        $objectTypeResolver = $relationalTypeResolver;
        if ($fieldOrDirectiveArgs) {
            foreach ($fieldOrDirectiveArgs as $argName => $argValue) {
                // Validate it
                if ($maybeErrors = $this->resolveFieldArgumentValueErrorQualifiedEntriesForSchema($objectTypeResolver, $argValue, $variables)) {
                    foreach ($maybeErrors as $schemaError) {
                        array_unshift($schemaError[Tokens::PATH], $fieldOrDirective);
                        $this->prependPathOnNestedErrors($schemaError, $fieldOrDirective);
                        $schemaErrors[] = $schemaError;
                    }
                    // Because it's an error, set the value to null, so it will be filtered out
                    $fieldOrDirectiveArgs[$argName] = null;
                }
                // Find warnings and deprecations
                if ($maybeWarnings = $this->resolveFieldArgumentValueWarningQualifiedEntriesForSchema($objectTypeResolver, $argValue, $variables)) {
                    foreach ($maybeWarnings as $schemaWarning) {
                        array_unshift($schemaWarning[Tokens::PATH], $fieldOrDirective);
                        $schemaWarnings[] = $schemaWarning;
                    }
                }
                if ($maybeDeprecations = $this->resolveFieldArgumentValueDeprecationQualifiedEntriesForSchema($objectTypeResolver, $argValue, $variables)) {
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

    public function extractFieldArgumentsForObject(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $field,
        ?array $variables,
        ?array $expressions
    ): array {
        $objectErrors = $objectWarnings = $objectDeprecations = [];
        $validAndResolvedField = $field;
        $fieldName = $this->getFieldName($field);
        $extractedFieldArgs = $fieldArgs = $this->extractFieldArguments(
            $objectTypeResolver,
            $field,
            $variables
        );
        // Only need to extract arguments if they have fields or arrays
        $fieldOutputKey = $this->getFieldOutputKey($field);
        $fieldArgs = $this->extractFieldOrDirectiveArgumentsForObject($objectTypeResolver, $object, $fieldArgs, $fieldOutputKey, $variables, $expressions, $objectErrors);
        // Cast the values to their appropriate type. If casting fails, the value returns as null
        $castingObjectErrors = $castingObjectWarnings = [];
        $fieldArgs = $this->castAndValidateFieldArgumentsForObject($objectTypeResolver, $field, $fieldArgs, $castingObjectErrors, $castingObjectWarnings, $objectDeprecations);
        if ($castingObjectErrors || $castingObjectWarnings) {
            $id = $objectTypeResolver->getID($object);
            if ($castingObjectErrors) {
                $objectErrors[(string)$id] = $castingObjectErrors;
            }
            if ($castingObjectWarnings) {
                $objectWarnings[(string)$id] = $castingObjectWarnings;
            }
        }
        if ($objectErrors) {
            $validAndResolvedField = null;
        } elseif ($extractedFieldArgs != $fieldArgs) {
            // There are 2 reasons why the field might have changed:
            // 1. validField: There are $objectWarnings: remove the fieldArgs that failed
            // 2. resolvedField: Some fieldArg was a variable: replace it with its value
            $validAndResolvedField = $this->replaceFieldArgs($field, $fieldArgs);
        }
        return [
            $validAndResolvedField,
            $fieldName,
            $fieldArgs ?? [],
            $objectErrors,
            $objectWarnings,
            $objectDeprecations
        ];
    }

    public function extractDirectiveArgumentsForObject(
        DirectiveResolverInterface $directiveResolver,
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $object,
        string $fieldDirective,
        array $variables,
        array $expressions
    ): array {
        $objectErrors = $objectWarnings = $objectDeprecations = [];
        $validAndResolvedDirective = $fieldDirective;
        $directiveName = $this->getFieldDirectiveName($fieldDirective);
        $extractedDirectiveArgs = $directiveArgs = $this->extractDirectiveArguments(
            $directiveResolver,
            $relationalTypeResolver,
            $fieldDirective,
            $variables,
        );
        // Only need to extract arguments if they have fields or arrays
        $directiveOutputKey = $this->getDirectiveOutputKey($fieldDirective);
        $directiveArgs = $this->extractFieldOrDirectiveArgumentsForObject($relationalTypeResolver, $object, $directiveArgs, $directiveOutputKey, $variables, $expressions, $objectErrors);
        // Cast the values to their appropriate type. If casting fails, the value returns as null
        $castingObjectErrors = $castingObjectWarnings = [];
        $directiveArgs = $this->castAndValidateDirectiveArgumentsForObject($directiveResolver, $relationalTypeResolver, $fieldDirective, $directiveArgs, $castingObjectErrors, $castingObjectWarnings, $objectDeprecations);
        if ($castingObjectErrors || $castingObjectWarnings) {
            $id = $relationalTypeResolver->getID($object);
            if ($castingObjectErrors) {
                $objectErrors[(string)$id] = $castingObjectErrors;
            }
            if ($castingObjectWarnings) {
                $objectWarnings[(string)$id] = $castingObjectWarnings;
            }
        }
        if ($objectErrors) {
            $validAndResolvedDirective = null;
        } elseif ($extractedDirectiveArgs != $directiveArgs) {
            // There are 2 reasons why the fieldDirective might have changed:
            // 1. validField: There are $objectWarnings: remove the directiveArgs that failed
            // 2. resolvedField: Some directiveArg was a variable: replace it with its value
            $validAndResolvedDirective = $this->replaceFieldArgs($fieldDirective, $directiveArgs);
        }
        return [
            $validAndResolvedDirective,
            $directiveName,
            $directiveArgs,
            $objectErrors,
            $objectWarnings,
            $objectDeprecations,
            $objectDeprecations
        ];
    }

    protected function extractFieldOrDirectiveArgumentsForObject(
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $object,
        array $fieldOrDirectiveArgs,
        string $fieldOrDirectiveOutputKey,
        ?array $variables,
        ?array $expressions,
        array &$objectErrors
    ): array {
        // Only need to extract arguments if they have fields or arrays
        if (
            FieldQueryUtils::isAnyFieldArgumentValueDynamic(
                array_values(
                    $fieldOrDirectiveArgs
                )
            )
        ) {
            $id = $relationalTypeResolver->getID($object);
            foreach ($fieldOrDirectiveArgs as $directiveArgName => $directiveArgValue) {
                $directiveArgValue = $this->maybeResolveFieldArgumentValueForObject($relationalTypeResolver, $object, $directiveArgValue, $variables, $expressions);
                // Validate it
                if (GeneralUtils::isError($directiveArgValue)) {
                    /** @var Error */
                    $error = $directiveArgValue;
                    if ($errorData = $error->getData()) {
                        $errorFieldOrDirective = $errorData[ErrorDataTokens::FIELD_NAME] ?? null;
                    }
                    $errorFieldOrDirective = $errorFieldOrDirective ?? $fieldOrDirectiveOutputKey;
                    $objectErrors[(string)$id][] = $this->getErrorService()->getErrorOutput($error, [$errorFieldOrDirective], $directiveArgName);
                    $fieldOrDirectiveArgs[$directiveArgName] = null;
                    continue;
                }
                $fieldOrDirectiveArgs[$directiveArgName] = $directiveArgValue;
            }
            return $this->filterFieldOrDirectiveArgs($fieldOrDirectiveArgs);
        }
        return $fieldOrDirectiveArgs;
    }

    /**
     * @param array<string,Error> $failedCastingDirectiveArgErrors
     */
    protected function castDirectiveArguments(
        DirectiveResolverInterface $directiveResolver,
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $directive,
        array $directiveArgs,
        array &$failedCastingDirectiveArgErrors,
        array &$castingDirectiveArgDeprecationMessages,
        bool $forSchema
    ): array {
        $directiveArgSchemaDefinition = $this->getDirectiveSchemaDefinitionArgs($directiveResolver, $relationalTypeResolver);
        return $this->castFieldOrDirectiveArguments(
            $directiveArgs,
            $directiveArgSchemaDefinition,
            $failedCastingDirectiveArgErrors,
            $castingDirectiveArgDeprecationMessages,
            $forSchema
        );
    }

    /**
     * @param array<string,Error> $failedCastingFieldArgErrors
     */
    protected function castFieldArguments(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $field,
        array $fieldArgs,
        array &$failedCastingFieldArgErrors,
        array &$schemaOrObjectErrors,
        array &$castingFieldArgDeprecationMessages,
        bool $forSchema
    ): ?array {
        $fieldArgSchemaDefinition = $this->getFieldArgsSchemaDefinition($objectTypeResolver, $field);
        if ($fieldArgSchemaDefinition === null) {
            $schemaOrObjectErrors[] = [
                Tokens::PATH => [$field],
                Tokens::MESSAGE => $this->getNoFieldErrorMessage($objectTypeResolver, $field),
            ];
            return null;
        }
        return $this->castFieldOrDirectiveArguments(
            $fieldArgs,
            $fieldArgSchemaDefinition,
            $failedCastingFieldArgErrors,
            $castingFieldArgDeprecationMessages,
            $forSchema
        );
    }

    /**
     * @param array<string,Error> $failedCastingFieldOrDirectiveArgErrors
     */
    protected function castFieldOrDirectiveArguments(
        array $fieldOrDirectiveArgs,
        array $fieldOrDirectiveArgSchemaDefinition,
        array &$failedCastingFieldOrDirectiveArgErrors,
        array &$castingFieldOrDirectiveArgDeprecationMessages,
        bool $forSchema
    ): array {
        // Cast all argument values
        foreach ($fieldOrDirectiveArgs as $argName => $argValue) {
            /**
             * If the arg doesn't exist, there's already a warning about it missing
             * in the schema (not an error, in that case it's already not added)
             */
            if (!array_key_exists($argName, $fieldOrDirectiveArgSchemaDefinition)) {
                continue;
            }

            // There are 2 possibilities for casting:
            // 1. $forSchema = true: Cast all items except fields (eg: hasComments()) or arrays with fields (eg: [hasComments()])
            // 2. $forSchema = false: Should be cast only fields, however by now we can't tell which are fields and which are not, since fields have already been resolved to their value. Hence, cast everything (fieldArgValues that failed at the schema level will not be provided in the input array, so won't be validated twice)
            // Otherwise, simply add the argValue directly, it will be eventually casted by the other function
            if (
                !(
                !$forSchema
                // Conditions below are for `$forSchema => true`
                || (!is_array($argValue) && !$this->isFieldArgumentValueDynamic($argValue))
                || (is_array($argValue) && !FieldQueryUtils::isAnyFieldArgumentValueDynamic($argValue))
                )
            ) {
                $fieldOrDirectiveArgs[$argName] = $argValue;
                continue;
            }

            /** @var InputTypeResolverInterface */
            $fieldOrDirectiveArgTypeResolver = $fieldOrDirectiveArgSchemaDefinition[$argName][SchemaDefinition::TYPE_RESOLVER];

            /**
             * `DangerouslyDynamic` is a special scalar type which is not coerced or validated.
             * In particular, it does not need to validate if it is an array or not,
             * as according to the applied WrappingType.
             *
             * This is to enable it to have an array as value, which is not
             * allowed by GraphQL unless the array is explicitly defined.
             *
             * For instance, type `DangerouslyDynamic` could have values
             * `"hello"` and `["hello"]`, but in GraphQL we must differentiate
             * these values by types `String` and `[String]`.
             */
            if ($fieldOrDirectiveArgTypeResolver === $this->getDangerouslyDynamicScalarTypeResolver()) {
                $fieldOrDirectiveArgs[$argName] = $argValue;
                continue;
            }

            /**
             * Execute the validation, checking that the WrappingType is respected.
             * Eg: `["hello"]` must be `[String]`, can't be `[[String]]` or `String`.
             *
             * Coerce the value to the appropriate type.
             * Eg: from string to boolean.
             **/
            $fieldOrDirectiveArgIsArrayType = $fieldOrDirectiveArgSchemaDefinition[$argName][SchemaDefinition::IS_ARRAY] ?? false;
            $fieldOrDirectiveArgIsNonNullArrayItemsType = $fieldOrDirectiveArgSchemaDefinition[$argName][SchemaDefinition::IS_NON_NULLABLE_ITEMS_IN_ARRAY] ?? false;
            $fieldOrDirectiveArgIsArrayOfArraysType = $fieldOrDirectiveArgSchemaDefinition[$argName][SchemaDefinition::IS_ARRAY_OF_ARRAYS] ?? false;
            $fieldOrDirectiveArgIsNonNullArrayOfArraysItemsType = $fieldOrDirectiveArgSchemaDefinition[$argName][SchemaDefinition::IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS] ?? false;

            /**
             * Support passing a single value where a list is expected:
             * `{ posts(ids: 1) }` means `{ posts(ids: [1]) }`
             *
             * Defined in the GraphQL spec.
             *
             * @see https://spec.graphql.org/draft/#sec-List.Input-Coercion
             */
            $argValue = $this->getInputCoercingService()->maybeConvertInputValueFromSingleToList(
                $argValue,
                $fieldOrDirectiveArgIsArrayType,
                $fieldOrDirectiveArgIsArrayOfArraysType,
            );

            // Validate that the expected array/non-array input is provided
            $maybeErrorMessage = $this->getInputCoercingService()->validateInputArrayModifiers(
                $argValue,
                $argName,
                $fieldOrDirectiveArgIsArrayType,
                $fieldOrDirectiveArgIsNonNullArrayItemsType,
                $fieldOrDirectiveArgIsArrayOfArraysType,
                $fieldOrDirectiveArgIsNonNullArrayOfArraysItemsType,
            );
            if ($maybeErrorMessage !== null) {
                $failedCastingFieldOrDirectiveArgErrors[$argName] = new Error(
                    sprintf('%s-cast', $argName),
                    $maybeErrorMessage
                );
                unset($fieldOrDirectiveArgs[$argName]);
                continue;
            }

            // Cast (or "coerce" in GraphQL terms) the value
            $coercedArgValue = $this->getInputCoercingService()->coerceInputValue(
                $fieldOrDirectiveArgTypeResolver,
                $argValue,
                $fieldOrDirectiveArgIsArrayType,
                $fieldOrDirectiveArgIsArrayOfArraysType,
            );

            // Check if the coercion produced errors
            $maybeCoercedArgValueErrors = $this->getInputCoercingService()->extractErrorsFromCoercedInputValue(
                $coercedArgValue,
                $fieldOrDirectiveArgIsArrayType,
                $fieldOrDirectiveArgIsArrayOfArraysType,
            );
            if ($maybeCoercedArgValueErrors !== []) {
                $castingError = count($maybeCoercedArgValueErrors) === 1 ?
                    $maybeCoercedArgValueErrors[0]
                    : new Error(
                        'casting',
                        $this->__('Casting cannot be done due to nested errors', 'component-model'),
                        null,
                        $maybeCoercedArgValueErrors
                    );
                $failedCastingFieldOrDirectiveArgErrors[$argName] = $castingError;
                unset($fieldOrDirectiveArgs[$argName]);
                continue;
            }

            // Obtain the deprecations
            if ($fieldOrDirectiveArgTypeResolver instanceof DeprecatableInputTypeResolverInterface) {
                $deprecationMessages = $this->getInputCoercingService()->getInputValueDeprecationMessages(
                    $fieldOrDirectiveArgTypeResolver,
                    $coercedArgValue,
                    $fieldOrDirectiveArgIsArrayType,
                    $fieldOrDirectiveArgIsArrayOfArraysType,
                );
                $castingFieldOrDirectiveArgDeprecationMessages = array_merge(
                    $castingFieldOrDirectiveArgDeprecationMessages,
                    $deprecationMessages
                );
            }

            // No errors, assign the value
            $fieldOrDirectiveArgs[$argName] = $coercedArgValue;
        }
        return $fieldOrDirectiveArgs;
    }

    /**
     * @param array<string,Error> $failedCastingDirectiveArgErrors
     */
    protected function castDirectiveArgumentsForSchema(DirectiveResolverInterface $directiveResolver, RelationalTypeResolverInterface $relationalTypeResolver, string $fieldDirective, array $directiveArgs, array &$failedCastingDirectiveArgErrors, array &$castingDirectiveArgDeprecationMessages, bool $disableDynamicFields = false): array
    {
        // If the directive doesn't allow dynamic fields (Eg: <cacheControl(maxAge:id())>), then treat it as not for schema
        $forSchema = !$disableDynamicFields;
        return $this->castDirectiveArguments($directiveResolver, $relationalTypeResolver, $fieldDirective, $directiveArgs, $failedCastingDirectiveArgErrors, $castingDirectiveArgDeprecationMessages, $forSchema);
    }

    /**
     * @param array<string,Error> $failedCastingFieldArgErrors
     */
    protected function castFieldArgumentsForSchema(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $field,
        array $fieldArgs,
        array &$failedCastingFieldArgErrors,
        array &$schemaErrors,
        array &$castingFieldArgDeprecationMessages,
    ): ?array {
        return $this->castFieldArguments($objectTypeResolver, $field, $fieldArgs, $failedCastingFieldArgErrors, $schemaErrors, $castingFieldArgDeprecationMessages, true);
    }

    /**
     * @param array<string,Error> $failedCastingDirectiveArgErrors
     */
    protected function castDirectiveArgumentsForObject(DirectiveResolverInterface $directiveResolver, RelationalTypeResolverInterface $relationalTypeResolver, string $directive, array $directiveArgs, array &$failedCastingDirectiveArgErrors, array &$castingDirectiveArgDeprecationMessages): array
    {
        return $this->castDirectiveArguments($directiveResolver, $relationalTypeResolver, $directive, $directiveArgs, $failedCastingDirectiveArgErrors, $castingDirectiveArgDeprecationMessages, false);
    }

    /**
     * @param array<string,Error> $failedCastingFieldArgErrors
     */
    protected function castFieldArgumentsForObject(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $field,
        array $fieldArgs,
        array &$failedCastingFieldArgErrors,
        array &$objectErrors,
        array &$castingFieldArgDeprecationMessages,
    ): ?array {
        return $this->castFieldArguments($objectTypeResolver, $field, $fieldArgs, $failedCastingFieldArgErrors, $objectErrors, $castingFieldArgDeprecationMessages, false);
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

    protected function getFieldArgsSchemaDefinition(ObjectTypeResolverInterface $objectTypeResolver, string $field): ?array
    {
        $objectTypeResolverClass = get_class($objectTypeResolver);
        if (!array_key_exists($field, $this->fieldSchemaDefinitionArgsCache[$objectTypeResolverClass] ?? [])) {
            $fieldArgsSchemaDefinition = null;
            $fieldSchemaDefinition = $objectTypeResolver->getFieldSchemaDefinition($field);
            if ($fieldSchemaDefinition !== null) {
                $fieldArgsSchemaDefinition = $fieldSchemaDefinition[SchemaDefinition::ARGS] ?? [];
            }
            $this->fieldSchemaDefinitionArgsCache[$objectTypeResolverClass][$field] = $fieldArgsSchemaDefinition;
        }
        return $this->fieldSchemaDefinitionArgsCache[$objectTypeResolverClass][$field];
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
     * @return array<string, InputTypeResolverInterface>|null
     */
    protected function getFieldArgumentNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $field): ?array
    {
        $objectTypeResolverClass = get_class($objectTypeResolver);
        if (!array_key_exists($field, $this->fieldArgumentNameTypeResolversCache[$objectTypeResolverClass] ?? [])) {
            $this->fieldArgumentNameTypeResolversCache[$objectTypeResolverClass][$field] = $this->doGetFieldArgumentNameTypeResolvers($objectTypeResolver, $field);
        }
        /** @var array<string, InputTypeResolverInterface>|null */
        $fieldArgumentNameTypeResolvers = $this->fieldArgumentNameTypeResolversCache[$objectTypeResolverClass][$field];
        return $fieldArgumentNameTypeResolvers;
    }

    /**
     * @return array<string, InputTypeResolverInterface>|null
     */
    protected function doGetFieldArgumentNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $field): ?array
    {
        // Get the field argument types, to know to what type it will cast the value
        $fieldArgsSchemaDefinition = $this->getFieldArgsSchemaDefinition($objectTypeResolver, $field);
        if ($fieldArgsSchemaDefinition === null) {
            return null;
        }
        $fieldArgNameTypeResolvers = [];
        foreach ($fieldArgsSchemaDefinition as $fieldArgSchemaDefinition) {
            $fieldArgNameTypeResolvers[$fieldArgSchemaDefinition[SchemaDefinition::NAME]] = $fieldArgSchemaDefinition[SchemaDefinition::TYPE_RESOLVER];
        }
        return $fieldArgNameTypeResolvers;
    }

    protected function getFieldArgumentNameDefaultValues(ObjectTypeResolverInterface $objectTypeResolver, string $field): ?array
    {
        $objectTypeResolverClass = get_class($objectTypeResolver);
        if (!array_key_exists($field, $this->fieldArgumentNameDefaultValuesCache[$objectTypeResolverClass] ?? [])) {
            $this->fieldArgumentNameDefaultValuesCache[$objectTypeResolverClass][$field] = $this->doGetFieldArgumentNameDefaultValues($objectTypeResolver, $field);
        }
        return $this->fieldArgumentNameDefaultValuesCache[$objectTypeResolverClass][$field];
    }

    protected function doGetFieldArgumentNameDefaultValues(ObjectTypeResolverInterface $objectTypeResolver, string $field): ?array
    {
        // Get the field arguments which have a default value
        // Set the missing InputObject as {} to give it a chance to set its default input field values
        $fieldArgsSchemaDefinition = $this->getFieldArgsSchemaDefinition($objectTypeResolver, $field);
        if ($fieldArgsSchemaDefinition === null) {
            return null;
        }
        $fieldArgNameDefaultValues = [];
        foreach ($fieldArgsSchemaDefinition as $fieldSchemaDefinitionArg) {
            if (\array_key_exists(SchemaDefinition::DEFAULT_VALUE, $fieldSchemaDefinitionArg)) {
                // If it has a default value, set it
                $fieldArgNameDefaultValues[$fieldSchemaDefinitionArg[SchemaDefinition::NAME]] = $fieldSchemaDefinitionArg[SchemaDefinition::DEFAULT_VALUE];
            } elseif (
                // If it is a non-mandatory InputObject, set {}
                // (If it is mandatory, don't set a value as to let the validation fail)
                $fieldSchemaDefinitionArg[SchemaDefinition::TYPE_RESOLVER] instanceof InputObjectTypeResolverInterface
                && !($fieldSchemaDefinitionArg[SchemaDefinition::MANDATORY] ?? false)
            ) {
                $fieldArgNameDefaultValues[$fieldSchemaDefinitionArg[SchemaDefinition::NAME]] = new stdClass();
            }
        }
        return $fieldArgNameDefaultValues;
    }

    protected function castAndValidateDirectiveArgumentsForSchema(DirectiveResolverInterface $directiveResolver, RelationalTypeResolverInterface $relationalTypeResolver, string $fieldDirective, array $directiveArgs, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations, bool $disableDynamicFields = false): array
    {
        if ($directiveArgs) {
            $failedCastingDirectiveArgErrors = [];
            $castingDirectiveArgDeprecationMessages = [];
            $castedDirectiveArgs = $this->castDirectiveArgumentsForSchema($directiveResolver, $relationalTypeResolver, $fieldDirective, $directiveArgs, $failedCastingDirectiveArgErrors, $castingDirectiveArgDeprecationMessages, $disableDynamicFields);
            return $this->validateAndFilterCastDirectiveArguments($directiveResolver, $relationalTypeResolver, $castedDirectiveArgs, $failedCastingDirectiveArgErrors, $castingDirectiveArgDeprecationMessages, $fieldDirective, $directiveArgs, $schemaErrors, $schemaWarnings, $schemaDeprecations);
        }
        return $directiveArgs;
    }

    protected function castAndValidateFieldArgumentsForSchema(ObjectTypeResolverInterface $objectTypeResolver, string $field, array $fieldArgs, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations): ?array
    {
        if ($fieldArgs) {
            $failedCastingFieldArgErrors = [];
            $castingSchemaErrors = [];
            $castingFieldArgDeprecationMessages = [];
            $castedFieldArgs = $this->castFieldArgumentsForSchema($objectTypeResolver, $field, $fieldArgs, $failedCastingFieldArgErrors, $castingSchemaErrors, $castingFieldArgDeprecationMessages);
            if ($castingSchemaErrors) {
                $schemaErrors = array_merge(
                    $schemaErrors,
                    $castingSchemaErrors
                );
                return null;
            }
            return $this->validateAndFilterCastFieldArguments($objectTypeResolver, $castedFieldArgs, $failedCastingFieldArgErrors, $castingFieldArgDeprecationMessages, $field, $fieldArgs, $schemaErrors, $schemaWarnings, $schemaDeprecations);
        }
        return $fieldArgs;
    }

    protected function castAndValidateDirectiveArgumentsForObject(DirectiveResolverInterface $directiveResolver, RelationalTypeResolverInterface $relationalTypeResolver, string $fieldDirective, array $directiveArgs, array &$objectErrors, array &$objectWarnings, array &$objectDeprecations): ?array
    {
        $failedCastingDirectiveArgErrors = [];
        $castingDirectiveArgDeprecationMessages = [];
        $castedDirectiveArgs = $this->castDirectiveArgumentsForObject($directiveResolver, $relationalTypeResolver, $fieldDirective, $directiveArgs, $failedCastingDirectiveArgErrors, $castingDirectiveArgDeprecationMessages);
        return $this->validateAndFilterCastDirectiveArguments($directiveResolver, $relationalTypeResolver, $castedDirectiveArgs, $failedCastingDirectiveArgErrors, $castingDirectiveArgDeprecationMessages, $fieldDirective, $directiveArgs, $objectErrors, $objectWarnings, $objectDeprecations);
    }

    protected function castAndValidateFieldArgumentsForObject(ObjectTypeResolverInterface $objectTypeResolver, string $field, array $fieldArgs, array &$objectErrors, array &$objectWarnings, array &$objectDeprecations): ?array
    {
        $failedCastingFieldArgErrors = [];
        $castingObjectErrors = [];
        $castingFieldArgDeprecationMessages = [];
        $castedFieldArgs = $this->castFieldArgumentsForObject($objectTypeResolver, $field, $fieldArgs, $failedCastingFieldArgErrors, $castingObjectErrors, $castingFieldArgDeprecationMessages);
        if ($castingObjectErrors) {
            $objectErrors = array_merge(
                $objectErrors,
                $castingObjectErrors
            );
            return null;
        }
        return $this->validateAndFilterCastFieldArguments($objectTypeResolver, $castedFieldArgs, $failedCastingFieldArgErrors, $castingFieldArgDeprecationMessages, $field, $fieldArgs, $objectErrors, $objectWarnings, $objectDeprecations);
    }

    /**
     * @param array<string,Error> $failedCastingDirectiveArgErrors
     */
    protected function validateAndFilterCastDirectiveArguments(
        DirectiveResolverInterface $directiveResolver,
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $castedDirectiveArgs,
        array &$failedCastingDirectiveArgErrors,
        array &$castingDirectiveArgDeprecationMessages,
        string $fieldDirective,
        array $directiveArgs,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations,
    ): array {
        // Add the deprecations
        foreach ($castingDirectiveArgDeprecationMessages as $castingDirectiveArgDeprecationMessage) {
            $schemaDeprecations[] = [
                Tokens::PATH => [$fieldDirective],
                Tokens::MESSAGE => $castingDirectiveArgDeprecationMessage,
            ];
        }

        // If any casting can't be done, show an error
        if ($failedCastingDirectiveArgErrors) {
            $directiveName = $this->getFieldDirectiveName($fieldDirective);
            $directiveArgNameTypeResolvers = $this->getDirectiveArgumentNameTypeResolvers($directiveResolver, $relationalTypeResolver);
            $directiveArgNameSchemaDefinition = $this->getDirectiveSchemaDefinitionArgs($directiveResolver, $relationalTypeResolver);
            /** @var ComponentConfiguration */
            $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
            $treatTypeCoercingFailuresAsErrors = $componentConfiguration->treatTypeCoercingFailuresAsErrors();
            foreach (array_keys($failedCastingDirectiveArgErrors) as $failedCastingDirectiveArgName) {
                // If it is Error, also show the error message
                $directiveArgIsArrayType = $directiveArgNameSchemaDefinition[$failedCastingDirectiveArgName][SchemaDefinition::IS_ARRAY] ?? false;
                $directiveArgIsArrayOfArraysType = $directiveArgNameSchemaDefinition[$failedCastingDirectiveArgName][SchemaDefinition::IS_ARRAY_OF_ARRAYS] ?? false;
                $composedDirectiveArgTypeResolver = $directiveArgNameTypeResolvers[$failedCastingDirectiveArgName];
                $composedDirectiveArgTypeName = $composedDirectiveArgTypeResolver->getMaybeNamespacedTypeName();
                if ($directiveArgIsArrayOfArraysType) {
                    $composedDirectiveArgTypeName = sprintf(
                        $this->__('array of arrays of %s', 'pop-component-model'),
                        $composedDirectiveArgTypeName
                    );
                } elseif ($directiveArgIsArrayType) {
                    $composedDirectiveArgTypeName = sprintf(
                        $this->__('array of %s', 'pop-component-model'),
                        $composedDirectiveArgTypeName
                    );
                }
                $directiveArgError = $failedCastingDirectiveArgErrors[$failedCastingDirectiveArgName] ?? null;
                if ($directiveArgError !== null) {
                    $encodedValue = $directiveArgs[$failedCastingDirectiveArgName] instanceof stdClass || is_array($directiveArgs[$failedCastingDirectiveArgName])
                        ? json_encode($directiveArgs[$failedCastingDirectiveArgName])
                        : $directiveArgs[$failedCastingDirectiveArgName];
                    $directiveArgError = new Error(
                        sprintf('%s-error', $failedCastingDirectiveArgName),
                        sprintf(
                            $this->__('For directive \'%s\', casting value \'%s\' for argument \'%s\' to type \'%s\' failed', 'pop-component-model'),
                            $directiveName,
                            $encodedValue,
                            $failedCastingDirectiveArgName,
                            $composedDirectiveArgTypeName
                        )
                    );
                }
                // Either treat it as an error or a warning
                $schemaWarningOrError = $this->getErrorService()->getErrorOutput($directiveArgError, [$fieldDirective], $failedCastingDirectiveArgName);
                if ($treatTypeCoercingFailuresAsErrors) {
                    $schemaErrors[] = $schemaWarningOrError;
                } else {
                    // Override the message
                    $schemaWarningOrError[Tokens::MESSAGE] = sprintf(
                        $this->__('%1$s. It has been ignored', 'pop-component-model'),
                        $schemaWarningOrError[Tokens::MESSAGE]
                    );
                    $schemaWarnings[] = $schemaWarningOrError;
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

    /**
     * @param array<string,Error> $failedCastingFieldArgErrors
     */
    protected function validateAndFilterCastFieldArguments(
        ObjectTypeResolverInterface $objectTypeResolver,
        array $castedFieldArgs,
        array &$failedCastingFieldArgErrors,
        array &$castingFieldArgDeprecationMessages,
        string $field,
        array $fieldArgs,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations,
    ): array {
        // Add the deprecations
        foreach ($castingFieldArgDeprecationMessages as $castingFieldArgDeprecationMessage) {
            $schemaDeprecations[] = [
                Tokens::PATH => [$field],
                Tokens::MESSAGE => $castingFieldArgDeprecationMessage,
            ];
        }

        // If any casting can't be done, show an error
        if ($failedCastingFieldArgErrors) {
            $fieldName = $this->getFieldName($field);
            $fieldArgNameTypeResolvers = $this->getFieldArgumentNameTypeResolvers($objectTypeResolver, $field);
            if ($fieldArgNameTypeResolvers === null) {
                $schemaErrors[] = [
                    Tokens::PATH => [$field],
                    Tokens::MESSAGE => $this->getNoFieldErrorMessage($objectTypeResolver, $field),
                ];
                return $castedFieldArgs;
            }
            /** @var array */
            $fieldArgNameSchemaDefinition = $this->getFieldArgsSchemaDefinition($objectTypeResolver, $field);
            /** @var ComponentConfiguration */
            $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
            $treatTypeCoercingFailuresAsErrors = $componentConfiguration->treatTypeCoercingFailuresAsErrors();
            foreach (array_keys($failedCastingFieldArgErrors) as $failedCastingFieldArgName) {
                // If it is Error, also show the error message
                $fieldArgIsArrayType = $fieldArgNameSchemaDefinition[$failedCastingFieldArgName][SchemaDefinition::IS_ARRAY] ?? false;
                $fieldArgIsArrayOfArraysType = $fieldArgNameSchemaDefinition[$failedCastingFieldArgName][SchemaDefinition::IS_ARRAY_OF_ARRAYS] ?? false;
                $composedFieldArgTypeResolver = $fieldArgNameTypeResolvers[$failedCastingFieldArgName];
                $composedFieldArgTypeName = $composedFieldArgTypeResolver->getMaybeNamespacedTypeName();
                if ($fieldArgIsArrayOfArraysType) {
                    $composedFieldArgTypeName = sprintf(
                        $this->__('array of arrays of %s', 'pop-component-model'),
                        $composedFieldArgTypeName
                    );
                } elseif ($fieldArgIsArrayType) {
                    $composedFieldArgTypeName = sprintf(
                        $this->__('array of %s', 'pop-component-model'),
                        $composedFieldArgTypeName
                    );
                }
                $fieldArgError = $failedCastingFieldArgErrors[$failedCastingFieldArgName] ?? null;
                if ($fieldArgError === null) {
                    $encodedValue = $fieldArgs[$failedCastingFieldArgName] instanceof stdClass || is_array($fieldArgs[$failedCastingFieldArgName])
                        ? json_encode($fieldArgs[$failedCastingFieldArgName])
                        : $fieldArgs[$failedCastingFieldArgName];
                    $fieldArgError = new Error(
                        sprintf('%s-error', $failedCastingFieldArgName),
                        sprintf(
                            $this->__('For field \'%s\', casting value \'%s\' for argument \'%s\' to type \'%s\' failed', 'pop-component-model'),
                            $fieldName,
                            $encodedValue,
                            $failedCastingFieldArgName,
                            $composedFieldArgTypeName
                        )
                    );
                }
                // Either treat it as an error or a warning
                $schemaWarningOrError = $this->getErrorService()->getErrorOutput($fieldArgError, [$field], $failedCastingFieldArgName);
                if ($treatTypeCoercingFailuresAsErrors) {
                    $schemaErrors[] = $schemaWarningOrError;
                } else {
                    // Override the message
                    $schemaWarningOrError[Tokens::MESSAGE] = sprintf(
                        $this->__('%1$s. It has been ignored', 'pop-component-model'),
                        $schemaWarningOrError[Tokens::MESSAGE]
                    );
                    $schemaWarnings[] = $schemaWarningOrError;
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
            // If not passed the variables parameter, use $_REQUEST["variables"] by default
            if (is_null($variables)) {
                $vars = ApplicationState::getVars();
                $variables = \PoP\Root\App::getState('variables');
            }
            $variableName = substr($fieldArgValue, strlen(QuerySyntax::SYMBOL_VARIABLE_PREFIX));
            if (isset($variables[$variableName])) {
                return $variables[$variableName];
            }
            // If the variable is not set, then show the error under entry "variableErrors"
            $this->getFeedbackMessageStore()->addQueryError(sprintf(
                $this->__('Variable \'%s\' is undefined', 'pop-component-model'),
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
            $fieldOrDirectiveArgs = $this->filterFieldOrDirectiveArgs(
                array_map(function ($arrayValueElem) use ($variables) {
                    return $this->maybeConvertFieldArgumentValue($arrayValueElem, $variables);
                }, (array) $fieldArgValue)
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
        ?array $expressions
    ): mixed {
        // If it is an array, apply this function on all elements
        if (is_array($fieldArgValue)) {
            return array_map(
                function ($fieldArgValueElem) use ($relationalTypeResolver, $object, $variables, $expressions) {
                    return $this->maybeResolveFieldArgumentValueForObject($relationalTypeResolver, $object, $fieldArgValueElem, $variables, $expressions);
                },
                (array)$fieldArgValue
            );
        }

        // Execute as expression
        if ($this->isFieldArgumentValueAnExpression($fieldArgValue)) {
            // Expressions: allow to pass a field argument "key:%input%", which is passed when executing the directive through $expressions
            // Trim it so that "%{ self }%" is equivalent to "%{self}%". This is needed to set expressions through Symfony's DependencyInjection component (since %...% is reserved for its own parameters!)
            $expressionName = trim(substr($fieldArgValue, strlen(QuerySyntax::SYMBOL_EXPRESSION_OPENING), strlen($fieldArgValue) - strlen(QuerySyntax::SYMBOL_EXPRESSION_OPENING) - strlen(QuerySyntax::SYMBOL_EXPRESSION_CLOSING)));
            if (isset($expressions[$expressionName])) {
                return $expressions[$expressionName];
            }
            // If the expression is not set, then show the error under entry "expressionErrors"
            $this->getFeedbackMessageStore()->addQueryError(sprintf(
                $this->__('Expression \'%s\' is undefined', 'pop-component-model'),
                $expressionName
            ));
            return null;
        } elseif ($this->isFieldArgumentValueAField($fieldArgValue)) {
            // Execute as field
            // It is important to force the validation, because if a needed argument is provided with an error, it needs to be validated, casted and filtered out,
            // and if this wrong param is not "dynamic", then the validation would not take place
            $options = [
                AbstractRelationalTypeResolver::OPTION_VALIDATE_SCHEMA_ON_RESULT_ITEM => true,
            ];
            $resolvedValue = $relationalTypeResolver->resolveValue($object, (string)$fieldArgValue, $variables, $expressions, $options);
            if (GeneralUtils::isError($resolvedValue)) {
                // Show the error message, and return nothing
                /** @var Error */
                $error = $resolvedValue;
                $this->getFeedbackMessageStore()->addQueryError(sprintf(
                    $this->__('Executing field \'%s\' produced error: %s', 'pop-component-model'),
                    $fieldArgValue,
                    $error->getMessageOrCode()
                ));
                return null;
            }
            return $resolvedValue;
        }

        return $fieldArgValue;
    }

    protected function resolveFieldArgumentValueErrorQualifiedEntriesForSchema(ObjectTypeResolverInterface $objectTypeResolver, mixed $fieldArgValue, ?array $variables): array
    {
        // If it is an array, apply this function on all elements
        if (is_array($fieldArgValue)) {
            return GeneralUtils::arrayFlatten(array_filter(array_map(function ($fieldArgValueElem) use ($objectTypeResolver, $variables) {
                return $this->resolveFieldArgumentValueErrorQualifiedEntriesForSchema($objectTypeResolver, $fieldArgValueElem, $variables);
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

            // If it reached here, it's a field! Validate it, or show an error
            return $objectTypeResolver->resolveFieldValidationErrorQualifiedEntries($fieldArgValue, $variables);
        }

        return [];
    }

    protected function resolveFieldArgumentValueWarningQualifiedEntriesForSchema(ObjectTypeResolverInterface $objectTypeResolver, mixed $fieldArgValue, ?array $variables): array
    {
        // If it is an array, apply this function on all elements
        if (is_array($fieldArgValue)) {
            return GeneralUtils::arrayFlatten(array_filter(array_map(function ($fieldArgValueElem) use ($objectTypeResolver, $variables) {
                return $this->resolveFieldArgumentValueWarningQualifiedEntriesForSchema($objectTypeResolver, $fieldArgValueElem, $variables);
            }, $fieldArgValue)));
        }

        // If the result fieldArgValue is a field, then validate it and resolve it
        if ($this->isFieldArgumentValueAField($fieldArgValue)) {
            return $objectTypeResolver->resolveFieldValidationWarningQualifiedEntries($fieldArgValue, $variables);
        }

        return [];
    }

    protected function resolveFieldArgumentValueDeprecationQualifiedEntriesForSchema(ObjectTypeResolverInterface $objectTypeResolver, mixed $fieldArgValue, ?array $variables): array
    {
        // If it is an array, apply this function on all elements
        if (is_array($fieldArgValue)) {
            return GeneralUtils::arrayFlatten(array_filter(array_map(function ($fieldArgValueElem) use ($objectTypeResolver, $variables) {
                return $this->resolveFieldArgumentValueDeprecationQualifiedEntriesForSchema($objectTypeResolver, $fieldArgValueElem, $variables);
            }, $fieldArgValue)));
        }

        // If the result fieldArgValue is a field, then validate it and resolve it
        if ($this->isFieldArgumentValueAField($fieldArgValue)) {
            return $objectTypeResolver->resolveFieldDeprecationQualifiedEntries($fieldArgValue, $variables);
        }

        return [];
    }

    protected function getNoAliasFieldOutputKey(string $field): string
    {
        // GraphQL: Use fieldName only
        $vars = ApplicationState::getVars();
        if (\PoP\Root\App::getState('only-fieldname-as-outputkey') ?? null) {
            return $this->getFieldName($field);
        }
        return parent::getNoAliasFieldOutputKey($field);
    }

    protected function serializeObject(object $object): string
    {
        return $this->getObjectSerializationManager()->serialize($object);
    }
}
