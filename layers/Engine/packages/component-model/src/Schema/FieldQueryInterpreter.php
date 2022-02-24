<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\Component;
use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Error\Error;
use PoP\ComponentModel\Exception\SchemaReferenceException;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\Feedback\SchemaInputValidationFeedback;
use PoP\ComponentModel\Feedback\SchemaInputValidationFeedbackStore;
use PoP\ComponentModel\Feedback\Tokens;
use PoP\ComponentModel\FeedbackItemProviders\FeedbackItemProvider;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\ObjectSerialization\ObjectSerializationManagerInterface;
use PoP\ComponentModel\Resolvers\ResolverTypes;
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
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\Root\App;
use PoP\Root\FeedbackItemProviders\GenericFeedbackItemProvider;
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
                throw new SchemaReferenceException(
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
        array $variables,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): array {
        $variablesHash = $this->getVariablesHash($variables);
        $relationalTypeResolverClass = get_class($relationalTypeResolver);
        if (!isset($this->extractedDirectiveArgumentsCache[$relationalTypeResolverClass][$fieldDirective][$variablesHash])) {
            $this->extractedDirectiveArgumentsCache[$relationalTypeResolverClass][$fieldDirective][$variablesHash] = $this->doExtractDirectiveArguments(
                $directiveResolver,
                $relationalTypeResolver,
                $fieldDirective,
                $variables,
                $objectTypeFieldResolutionFeedbackStore,
            );
        }
        return $this->extractedDirectiveArgumentsCache[$relationalTypeResolverClass][$fieldDirective][$variablesHash];
    }

    protected function doExtractDirectiveArguments(
        DirectiveResolverInterface $directiveResolver,
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $fieldDirective,
        ?array $variables,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): array {
        $directiveArgumentNameDefaultValues = $this->getDirectiveArgumentNameDefaultValues($directiveResolver, $relationalTypeResolver);
        // Iterate all the elements, and extract them into the array
        if ($directiveArgElems = QueryHelpers::getFieldArgElements($this->getFieldDirectiveArgs($fieldDirective))) {
            $directiveArgumentNameTypeResolvers = $this->getDirectiveArgumentNameTypeResolvers($directiveResolver, $relationalTypeResolver);
            $orderedDirectiveArgNamesEnabled = $directiveResolver->enableOrderedSchemaDirectiveArgs($relationalTypeResolver);
            return $this->extractAndValidateFielOrDirectiveArguments(
                $relationalTypeResolver,
                $fieldDirective,
                $directiveArgElems,
                $orderedDirectiveArgNamesEnabled,
                $directiveArgumentNameTypeResolvers,
                $directiveArgumentNameDefaultValues,
                $variables,
                $objectTypeFieldResolutionFeedbackStore,
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
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $fieldOrDirective,
        array $fieldOrDirectiveArgElems,
        bool $orderedFieldOrDirectiveArgNamesEnabled,
        array $fieldOrDirectiveArgumentNameTypeResolvers,
        array $fieldOrDirectiveArgumentNameDefaultValues,
        ?array $variables,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        string $resolverType,
    ): array {
        if ($orderedFieldOrDirectiveArgNamesEnabled) {
            $orderedFieldOrDirectiveArgNames = array_keys($fieldOrDirectiveArgumentNameTypeResolvers);
        }
        $fieldOrDirectiveArgs = [];
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
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
                        $this->__('The argument on position number %s (with value \'%s\') has its name missing, and %s. Please define the query using the \'key%svalue\' format', 'component-model'),
                        $i + 1,
                        $fieldOrDirectiveArgValue,
                        $orderedFieldOrDirectiveArgNamesEnabled ?
                            $this->__('documentation for this argument in the schema definition has not been defined, hence it can\'t be deduced from there', 'component-model') :
                            $this->__('retrieving this information from the schema definition is not enabled for the field', 'component-model'),
                        QuerySyntax::SYMBOL_FIELDARGS_ARGKEYVALUESEPARATOR
                    );
                    if ($resolverType === ResolverTypes::DIRECTIVE && !$setFailingFieldResponseAsNull) {
                        $errorMessage = sprintf(
                            $this->__('%s. The directive has been ignored', 'component-model'),
                            $errorMessage
                        );
                    }
                    $objectTypeFieldResolutionFeedbackStore->addError(
                        new ObjectTypeFieldResolutionFeedback(
                            new FeedbackItemResolution(
                                GenericFeedbackItemProvider::class,
                                GenericFeedbackItemProvider::E1,
                                [
                                    $errorMessage,
                                ]
                            ),
                            LocationHelper::getNonSpecificLocation(),
                            $relationalTypeResolver,
                        )
                    );
                    // Ignore extracting this argument
                    continue;
                }
                $fieldOrDirectiveArgName = $orderedFieldOrDirectiveArgNames[$i];
                // // Log the found fieldOrDirectiveArgName
                // // Must re-cast to this package's class, to avoid IDE showing error
                // /** @var FeedbackMessageStoreInterface */
                // $feedbackMessageStore = $this->getFeedbackMessageStore();
                // $feedbackMessageStore->maybeAddLogEntry(
                //     sprintf(
                //         $this->__('In field or directive \'%s\', the argument on position number %s (with value \'%s\') is resolved as argument \'%s\'', 'component-model'),
                //         $fieldOrDirective,
                //         $i + 1,
                //         $fieldOrDirectiveArgValue,
                //         $fieldOrDirectiveArgName
                //     )
                // );
            } else {
                $fieldOrDirectiveArgName = trim(substr($fieldOrDirectiveArg, 0, $separatorPos));
                $fieldOrDirectiveArgValue = trim(substr($fieldOrDirectiveArg, $separatorPos + strlen(QuerySyntax::SYMBOL_FIELDARGS_ARGKEYVALUESEPARATOR)));
                // Validate that this argument exists in the schema, or show a warning if not
                // But don't skip it! It may be that the engine accepts the property, it is just not documented!
                if (!array_key_exists($fieldOrDirectiveArgName, $fieldOrDirectiveArgumentNameTypeResolvers)) {
                    $errorMessage = sprintf(
                        $this->__('On %1$s \'%2$s\', there is no argument with name \'%3$s\'', 'component-model'),
                        $resolverType == ResolverTypes::FIELD ? $this->__('field', 'component-model') : $this->__('directive', 'component-model'),
                        $this->getFieldName($fieldOrDirective),
                        $fieldOrDirectiveArgName
                    );
                    $objectTypeFieldResolutionFeedbackStore->addError(
                        new ObjectTypeFieldResolutionFeedback(
                            new FeedbackItemResolution(
                                GenericFeedbackItemProvider::class,
                                GenericFeedbackItemProvider::E1,
                                [
                                    $errorMessage,
                                ]
                            ),
                            LocationHelper::getNonSpecificLocation(),
                            $relationalTypeResolver,
                        )
                    );
                    if ($resolverType === ResolverTypes::DIRECTIVE && !$setFailingFieldResponseAsNull) {
                        $errorMessage = sprintf(
                            $this->__('%s. The directive has been ignored', 'component-model'),
                            $errorMessage
                        );
                    }
                    continue;
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
        array $variables,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): ?array {
        $variablesHash = $this->getVariablesHash($variables);
        $objectTypeResolverClass = get_class($objectTypeResolver);
        if (!array_key_exists($variablesHash, $this->extractedFieldArgumentsCache[$objectTypeResolverClass][$field] ?? [])) {
            $this->extractedFieldArgumentsCache[$objectTypeResolverClass][$field][$variablesHash] = $this->doExtractFieldArguments(
                $objectTypeResolver,
                $field,
                $variables,
                $objectTypeFieldResolutionFeedbackStore,
            );
        }
        return $this->extractedFieldArgumentsCache[$objectTypeResolverClass][$field][$variablesHash];
    }

    protected function doExtractFieldArguments(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $field,
        ?array $variables,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): ?array {
        // Iterate all the elements, and extract them into the array
        $fieldArgumentNameTypeResolvers = $this->getFieldArgumentNameTypeResolvers($objectTypeResolver, $field);
        if ($fieldArgumentNameTypeResolvers === null) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    $this->getNoFieldErrorFeedbackItemResolution($objectTypeResolver, $field),
                    LocationHelper::getNonSpecificLocation(),
                    $objectTypeResolver,
                )
            );
            return null;
        }
        /** @var array */
        $fieldOrDirectiveArgumentNameDefaultValues = $this->getFieldArgumentNameDefaultValues($objectTypeResolver, $field);
        if ($fieldArgElems = QueryHelpers::getFieldArgElements($this->getFieldArgs($field))) {
            /** @var array */
            $fieldSchemaDefinition = $objectTypeResolver->getFieldSchemaDefinition($field);
            $orderedFieldArgsEnabled = $fieldSchemaDefinition[SchemaDefinition::ORDERED_ARGS_ENABLED] ?? false;
            return $this->extractAndValidateFielOrDirectiveArguments(
                $objectTypeResolver,
                $field,
                $fieldArgElems,
                $orderedFieldArgsEnabled,
                $fieldArgumentNameTypeResolvers,
                $fieldOrDirectiveArgumentNameDefaultValues,
                $variables,
                $objectTypeFieldResolutionFeedbackStore,
                ResolverTypes::FIELD
            );
        }

        return $fieldOrDirectiveArgumentNameDefaultValues;
    }

    protected function getNoFieldErrorFeedbackItemResolution(ObjectTypeResolverInterface $objectTypeResolver, string $field): FeedbackItemResolution
    {
        return new FeedbackItemResolution(
            FeedbackItemProvider::class,
            FeedbackItemProvider::E16,
            [
                $this->getFieldName($field),
                $objectTypeResolver->getMaybeNamespacedTypeName()
            ]
        );
    }

    public function extractFieldArgumentsForSchema(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $field,
        array $variables,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): array {
        $validAndResolvedField = $field;
        $fieldName = $this->getFieldName($field);
        $separateObjectTypeFieldResolutionFeedbackStore = new ObjectTypeFieldResolutionFeedbackStore();
        $extractedFieldArgs = $fieldArgs = $this->extractFieldArguments(
            $objectTypeResolver,
            $field,
            $variables,
            $separateObjectTypeFieldResolutionFeedbackStore,
        );
        $objectTypeFieldResolutionFeedbackStore->incorporate($separateObjectTypeFieldResolutionFeedbackStore);
        // If there is no resolver for the field, we will already have an error by now
        if ($separateObjectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return [
                null,
                $fieldName,
                $fieldArgs ?? [],
            ];
        }
        $separateObjectTypeFieldResolutionFeedbackStore = new ObjectTypeFieldResolutionFeedbackStore();
        $fieldArgs = $this->validateExtractedFieldOrDirectiveArgumentsForSchema($objectTypeResolver, $field, $fieldArgs, $variables, $separateObjectTypeFieldResolutionFeedbackStore);
        // Cast the values to their appropriate type. If casting fails, the value returns as null
        $fieldArgs = $this->castAndValidateFieldArgumentsForSchema($objectTypeResolver, $field, $fieldArgs, $separateObjectTypeFieldResolutionFeedbackStore);
        $objectTypeFieldResolutionFeedbackStore->incorporate($separateObjectTypeFieldResolutionFeedbackStore);

        // If there's an error, those args will be removed. Then, re-create the fieldDirective to pass it to the function below
        if ($separateObjectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            $validAndResolvedField = null;
        } elseif ($extractedFieldArgs !== $fieldArgs) {
            // There are 2 reasons why the field might have changed:
            // 1. validField: There are $schemaWarnings: remove the fieldArgs that failed
            // 2. resolvedField: Some fieldArg was a variable: replace it with its value
            $validAndResolvedField = $this->replaceFieldArgs($field, $fieldArgs);
        }
        return [
            $validAndResolvedField,
            $fieldName,
            $fieldArgs,
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
        array $fieldDirectiveFields,
        array $variables,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
        bool $disableDynamicFields = false
    ): array {
        $validAndResolvedDirective = $fieldDirective;
        $directiveName = $this->getFieldDirectiveName($fieldDirective);
        $objectTypeFieldResolutionFeedbackStore = new ObjectTypeFieldResolutionFeedbackStore();
        $extractedDirectiveArgs = $directiveArgs = $this->extractDirectiveArguments(
            $directiveResolver,
            $relationalTypeResolver,
            $fieldDirective,
            $variables,
            $objectTypeFieldResolutionFeedbackStore,
        );
        $directiveArgs = $this->validateExtractedFieldOrDirectiveArgumentsForSchema($relationalTypeResolver, $fieldDirective, $directiveArgs, $variables, $objectTypeFieldResolutionFeedbackStore);
        // Cast the values to their appropriate type. If casting fails, the value returns as null
        $directiveArgs = $this->castAndValidateDirectiveArgumentsForSchema($directiveResolver, $relationalTypeResolver, $fieldDirective, $directiveArgs, $objectTypeFieldResolutionFeedbackStore, $disableDynamicFields);
        // Enable the directiveResolver to add its own code validations
        $directiveArgs = $directiveResolver->validateDirectiveArgumentsForSchema($relationalTypeResolver, $directiveName, $directiveArgs, $objectTypeFieldResolutionFeedbackStore);
        // Transfer the feedback
        foreach ($fieldDirectiveFields as $fieldDirective => $fields) {
            foreach ($fields as $field) {
                $engineIterationFeedbackStore->schemaFeedbackStore->incorporate(
                    $objectTypeFieldResolutionFeedbackStore,
                    $relationalTypeResolver,
                    $field,
                );
            }
        }

        // If there's an error, those args will be removed. Then, re-create the fieldDirective to pass it to the function below
        if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
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

    protected function validateExtractedFieldOrDirectiveArgumentsForSchema(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $fieldOrDirective,
        array $fieldOrDirectiveArgs,
        array $variables,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): array {
        // The UnionTypeResolver cannot validate, it needs to delegate to each targetObjectTypeResolver
        // which will be done on Object, not on Schema.
        // This situation can only happen for Directive
        // (which can receive RelationalType), not for Field (which receives ObjectType)
        if ($relationalTypeResolver instanceof UnionTypeResolverInterface) {
            return $fieldOrDirectiveArgs;
        }
        /** @var ObjectTypeResolverInterface */
        $objectTypeResolver = $relationalTypeResolver;
        foreach ($fieldOrDirectiveArgs as $argName => $argValue) {
            // Nested dynamic fields: Validation, warnings and deprecations
            $this->collectFieldArgumentValueErrorQualifiedEntriesForSchema($objectTypeResolver, $argValue, $variables, $objectTypeFieldResolutionFeedbackStore);
            $this->collectFieldArgumentValueWarningQualifiedEntriesForSchema($objectTypeResolver, $argValue, $variables, $objectTypeFieldResolutionFeedbackStore);
            $this->collectFieldArgumentValueDeprecationQualifiedEntriesForSchema($objectTypeResolver, $argValue, $variables, $objectTypeFieldResolutionFeedbackStore);
        }
        return $fieldOrDirectiveArgs;
    }

    public function extractFieldArgumentsForObject(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $field,
        array $variables,
        array $expressions,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): array {
        $validAndResolvedField = $field;
        $fieldName = $this->getFieldName($field);
        $extractedFieldArgs = $fieldArgs = $this->extractFieldArguments(
            $objectTypeResolver,
            $field,
            $variables,
            $objectTypeFieldResolutionFeedbackStore
        );
        // Only need to extract arguments if they have fields or arrays
        $fieldOutputKey = $this->getFieldOutputKey($field);
        $separateObjectTypeFieldResolutionFeedbackStore = new ObjectTypeFieldResolutionFeedbackStore();
        $fieldArgs = $this->extractFieldOrDirectiveArgumentsForObject($objectTypeResolver, $object, $fieldArgs, $fieldOutputKey, $variables, $expressions, $separateObjectTypeFieldResolutionFeedbackStore);
        // Cast the values to their appropriate type. If casting fails, the value returns as null
        $fieldArgs = $this->castAndValidateFieldArgumentsForObject($objectTypeResolver, $field, $fieldArgs, $objectTypeFieldResolutionFeedbackStore);
        $objectTypeFieldResolutionFeedbackStore->incorporate($separateObjectTypeFieldResolutionFeedbackStore);
        if ($separateObjectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            $validAndResolvedField = null;
        } elseif ($extractedFieldArgs !== $fieldArgs) {
            // There are 2 reasons why the field might have changed:
            // 1. validField: There are $objectWarnings: remove the fieldArgs that failed
            // 2. resolvedField: Some fieldArg was a variable: replace it with its value
            $validAndResolvedField = $this->replaceFieldArgs($field, $fieldArgs);
        }
        return [
            $validAndResolvedField,
            $fieldName,
            $fieldArgs ?? [],
        ];
    }

    public function extractDirectiveArgumentsForObject(
        DirectiveResolverInterface $directiveResolver,
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $object,
        array $fields,
        string $fieldDirective,
        array $variables,
        array $expressions,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): array {
        $objectErrors = $objectWarnings = $objectDeprecations = [];
        $validAndResolvedDirective = $fieldDirective;
        $directiveName = $this->getFieldDirectiveName($fieldDirective);
        $objectTypeFieldResolutionFeedbackStore = new ObjectTypeFieldResolutionFeedbackStore();
        $extractedDirectiveArgs = $directiveArgs = $this->extractDirectiveArguments(
            $directiveResolver,
            $relationalTypeResolver,
            $fieldDirective,
            $variables,
            $objectTypeFieldResolutionFeedbackStore,
        );
        // Only need to extract arguments if they have fields or arrays
        $directiveOutputKey = $this->getDirectiveOutputKey($fieldDirective);
        $directiveArgs = $this->extractFieldOrDirectiveArgumentsForObject($relationalTypeResolver, $object, $directiveArgs, $directiveOutputKey, $variables, $expressions, $objectTypeFieldResolutionFeedbackStore);
        // Cast the values to their appropriate type. If casting fails, the value returns as null
        $directiveArgs = $this->castAndValidateDirectiveArgumentsForObject($directiveResolver, $relationalTypeResolver, $fieldDirective, $directiveArgs, $objectTypeFieldResolutionFeedbackStore);
        // Transfer the feedback
        $objectID = $relationalTypeResolver->getID($object);
        foreach ($fields as $field) {
            $engineIterationFeedbackStore->objectFeedbackStore->incorporate(
                $objectTypeFieldResolutionFeedbackStore,
                $relationalTypeResolver,
                $field,
                $objectID,
            );
        }
        if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
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
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): array {
        // Only need to extract arguments if they have fields or arrays
        if (
            FieldQueryUtils::isAnyFieldArgumentValueDynamic(
                array_values(
                    $fieldOrDirectiveArgs
                )
            )
        ) {
            foreach ($fieldOrDirectiveArgs as $fieldOrDirectiveArgName => $fieldOrDirectiveArgValue) {
                $separateObjectTypeFieldResolutionFeedbackStore = new ObjectTypeFieldResolutionFeedbackStore();
                $fieldOrDirectiveArgValue = $this->maybeResolveFieldArgumentValueForObject($relationalTypeResolver, $object, $fieldOrDirectiveArgValue, $variables, $expressions, $separateObjectTypeFieldResolutionFeedbackStore);
                $objectTypeFieldResolutionFeedbackStore->incorporate($separateObjectTypeFieldResolutionFeedbackStore);
                // Validate it
                if ($separateObjectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
                    $fieldOrDirectiveArgs[$fieldOrDirectiveArgName] = null;
                    continue;
                }
                $fieldOrDirectiveArgs[$fieldOrDirectiveArgName] = $fieldOrDirectiveArgValue;
            }
        }
        return $fieldOrDirectiveArgs;
    }

    protected function castDirectiveArguments(
        DirectiveResolverInterface $directiveResolver,
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $directive,
        array $directiveArgs,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        bool $forSchema
    ): array {
        $directiveArgSchemaDefinition = $this->getDirectiveSchemaDefinitionArgs($directiveResolver, $relationalTypeResolver);
        $schemaInputValidationFeedbackStore = new SchemaInputValidationFeedbackStore();
        $castDirectiveArguments = $this->castFieldOrDirectiveArguments(
            $relationalTypeResolver,
            $directiveArgs,
            $directiveArgSchemaDefinition,
            $schemaInputValidationFeedbackStore,
            $forSchema
        );
        $objectTypeFieldResolutionFeedbackStore->incorporateSchemaInputValidation(
            $schemaInputValidationFeedbackStore,
            $relationalTypeResolver,
        );
        return $castDirectiveArguments;
    }

    protected function castFieldArguments(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $field,
        array $fieldArgs,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        bool $forSchema
    ): ?array {
        $fieldArgSchemaDefinition = $this->getFieldArgsSchemaDefinition($objectTypeResolver, $field);
        if ($fieldArgSchemaDefinition === null) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    $this->getNoFieldErrorFeedbackItemResolution($objectTypeResolver, $field),
                    LocationHelper::getNonSpecificLocation(),
                    $objectTypeResolver,
                )
            );
            return null;
        }
        $schemaInputValidationFeedbackStore = new SchemaInputValidationFeedbackStore();
        $castFieldArguments = $this->castFieldOrDirectiveArguments(
            $objectTypeResolver,
            $fieldArgs,
            $fieldArgSchemaDefinition,
            $schemaInputValidationFeedbackStore,
            $forSchema
        );
        $objectTypeFieldResolutionFeedbackStore->incorporateSchemaInputValidation(
            $schemaInputValidationFeedbackStore,
            $objectTypeResolver,
        );
        return $castFieldArguments;
    }

    protected function castFieldOrDirectiveArguments(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $fieldOrDirectiveArgs,
        array $fieldOrDirectiveArgSchemaDefinition,
        SchemaInputValidationFeedbackStore $schemaInputValidationFeedbackStore,
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
            $separateSchemaInputValidationFeedbackStore = new SchemaInputValidationFeedbackStore();
            $this->getInputCoercingService()->validateInputArrayModifiers(
                $fieldOrDirectiveArgTypeResolver,
                $argValue,
                $argName,
                $fieldOrDirectiveArgIsArrayType,
                $fieldOrDirectiveArgIsNonNullArrayItemsType,
                $fieldOrDirectiveArgIsArrayOfArraysType,
                $fieldOrDirectiveArgIsNonNullArrayOfArraysItemsType,
                $separateSchemaInputValidationFeedbackStore,
            );
            $schemaInputValidationFeedbackStore->incorporate($separateSchemaInputValidationFeedbackStore);
            if ($separateSchemaInputValidationFeedbackStore->getErrors() !== []) {
                $fieldOrDirectiveArgs[$argName] = null;
                continue;
            }

            // Cast (or "coerce" in GraphQL terms) the value
            $separateSchemaInputValidationFeedbackStore = new SchemaInputValidationFeedbackStore();
            $coercedArgValue = $this->getInputCoercingService()->coerceInputValue(
                $fieldOrDirectiveArgTypeResolver,
                $argValue,
                $fieldOrDirectiveArgIsArrayType,
                $fieldOrDirectiveArgIsArrayOfArraysType,
                $separateSchemaInputValidationFeedbackStore,
            );
            $schemaInputValidationFeedbackStore->incorporate($separateSchemaInputValidationFeedbackStore);
            if ($separateSchemaInputValidationFeedbackStore->getErrors() !== []) {
                $fieldOrDirectiveArgs[$argName] = null;
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
                foreach ($deprecationMessages as $deprecationMessage) {
                    $schemaInputValidationFeedbackStore->addDeprecation(
                        new SchemaInputValidationFeedback(
                            new FeedbackItemResolution(
                                GenericFeedbackItemProvider::class,
                                GenericFeedbackItemProvider::D1,
                                [
                                    $deprecationMessage,
                                ]
                            ),
                            LocationHelper::getNonSpecificLocation(),
                            $fieldOrDirectiveArgTypeResolver,
                        )
                    );
                }
            }

            // No errors, assign the value
            $fieldOrDirectiveArgs[$argName] = $coercedArgValue;
        }
        return $fieldOrDirectiveArgs;
    }

    protected function castDirectiveArgumentsForSchema(
        DirectiveResolverInterface $directiveResolver,
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $fieldDirective,
        array $directiveArgs,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        bool $disableDynamicFields = false
    ): array {
        // If the directive doesn't allow dynamic fields (Eg: <cacheControl(maxAge:id())>), then treat it as not for schema
        $forSchema = !$disableDynamicFields;
        return $this->castDirectiveArguments($directiveResolver, $relationalTypeResolver, $fieldDirective, $directiveArgs, $objectTypeFieldResolutionFeedbackStore, $forSchema);
    }

    protected function castFieldArgumentsForSchema(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $field,
        array $fieldArgs,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): ?array {
        return $this->castFieldArguments($objectTypeResolver, $field, $fieldArgs, $objectTypeFieldResolutionFeedbackStore, true);
    }

    protected function castDirectiveArgumentsForObject(
        DirectiveResolverInterface $directiveResolver,
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $directive,
        array $directiveArgs,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): array {
        return $this->castDirectiveArguments($directiveResolver, $relationalTypeResolver, $directive, $directiveArgs, $objectTypeFieldResolutionFeedbackStore, false);
    }

    protected function castFieldArgumentsForObject(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $field,
        array $fieldArgs,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): ?array {
        return $this->castFieldArguments($objectTypeResolver, $field, $fieldArgs, $objectTypeFieldResolutionFeedbackStore, false);
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

    protected function castAndValidateDirectiveArgumentsForSchema(
        DirectiveResolverInterface $directiveResolver,
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $fieldDirective,
        array $directiveArgs,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        bool $disableDynamicFields = false
    ): array {
        if ($directiveArgs) {
            $separateObjectTypeFieldResolutionFeedbackStore = new ObjectTypeFieldResolutionFeedbackStore();
            $castedDirectiveArgs = $this->castDirectiveArgumentsForSchema($directiveResolver, $relationalTypeResolver, $fieldDirective, $directiveArgs, $separateObjectTypeFieldResolutionFeedbackStore, $disableDynamicFields);
            $objectTypeFieldResolutionFeedbackStore->incorporate($separateObjectTypeFieldResolutionFeedbackStore);
            if ($separateObjectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
                return null;
            }
            return $castedDirectiveArgs;
        }
        return $directiveArgs;
    }

    protected function castAndValidateFieldArgumentsForSchema(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $field,
        array $fieldArgs,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): ?array {
        if ($fieldArgs) {
            $separateObjectTypeFieldResolutionFeedbackStore = new ObjectTypeFieldResolutionFeedbackStore();
            $castedFieldArgs = $this->castFieldArgumentsForSchema($objectTypeResolver, $field, $fieldArgs, $separateObjectTypeFieldResolutionFeedbackStore);
            $objectTypeFieldResolutionFeedbackStore->incorporate($separateObjectTypeFieldResolutionFeedbackStore);
            if ($separateObjectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
                return null;
            }
            return $castedFieldArgs;
        }
        return $fieldArgs;
    }

    protected function castAndValidateDirectiveArgumentsForObject(
        DirectiveResolverInterface $directiveResolver,
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $fieldDirective,
        array $directiveArgs,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): ?array {
        if ($directiveArgs) {
            $separateObjectTypeFieldResolutionFeedbackStore = new ObjectTypeFieldResolutionFeedbackStore();
            $castedDirectiveArgs = $this->castDirectiveArgumentsForObject($directiveResolver, $relationalTypeResolver, $fieldDirective, $directiveArgs, $separateObjectTypeFieldResolutionFeedbackStore);
            $objectTypeFieldResolutionFeedbackStore->incorporate($separateObjectTypeFieldResolutionFeedbackStore);
            if ($separateObjectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
                return null;
            }
            return $castedDirectiveArgs;
        }
        return $directiveArgs;
    }

    protected function castAndValidateFieldArgumentsForObject(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $field,
        array $fieldArgs,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
    ): ?array {
        $separateObjectTypeFieldResolutionFeedbackStore = new ObjectTypeFieldResolutionFeedbackStore();
        $castedFieldArgs = $this->castFieldArgumentsForObject($objectTypeResolver, $field, $fieldArgs, $separateObjectTypeFieldResolutionFeedbackStore);
        $objectTypeFieldResolutionFeedbackStore->incorporate($separateObjectTypeFieldResolutionFeedbackStore);
        if ($separateObjectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return null;
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
            $expressionName = trim(substr($fieldArgValue, strlen(QuerySyntax::SYMBOL_EXPRESSION_OPENING), strlen($fieldArgValue) - strlen(QuerySyntax::SYMBOL_EXPRESSION_OPENING) - strlen(QuerySyntax::SYMBOL_EXPRESSION_CLOSING)));
            if (!isset($expressions[$expressionName])) {
                // If the expression is not set, then show the error under entry "expressionErrors"
                $objectTypeFieldResolutionFeedbackStore->addError(
                    new ObjectTypeFieldResolutionFeedback(
                        new FeedbackItemResolution(
                            FeedbackItemProvider::class,
                            FeedbackItemProvider::E14,
                            [
                                $expressionName,
                            ]
                        ),
                        LocationHelper::getNonSpecificLocation(),
                        $relationalTypeResolver,
                    )
                );
                return null;
            }
            return $expressions[$expressionName];
        }
        if ($this->isFieldArgumentValueAField($fieldArgValue)) {
            // Execute as field
            // It is important to force the validation, because if a needed argument is provided with an error, it needs to be validated, casted and filtered out,
            // and if this wrong param is not "dynamic", then the validation would not take place
            $options = [
                AbstractRelationalTypeResolver::OPTION_VALIDATE_SCHEMA_ON_RESULT_ITEM => true,
            ];
            $resolvedValue = $relationalTypeResolver->resolveValue(
                $object,
                (string)$fieldArgValue,
                $variables,
                $expressions,
                $objectTypeFieldResolutionFeedbackStore,
                $options
            );
            if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
                return null;
            }
            return $resolvedValue;
        }

        return $fieldArgValue;
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
                return;
            }

            // If it reached here, it's a field! Validate it, or show an error
            $objectTypeResolver->collectFieldValidationErrorQualifiedEntries($fieldArgValue, $variables, $objectTypeFieldResolutionFeedbackStore);
            return;
        }
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

        // If the result fieldArgValue is a field, then validate it and resolve it
        if ($this->isFieldArgumentValueAField($fieldArgValue)) {
            $objectTypeResolver->collectFieldValidationWarningQualifiedEntries($fieldArgValue, $variables, $objectTypeFieldResolutionFeedbackStore);
            return;
        }
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

        // If the result fieldArgValue is a field, then validate it and resolve it
        if ($this->isFieldArgumentValueAField($fieldArgValue)) {
            $objectTypeResolver->collectFieldDeprecationQualifiedEntries($fieldArgValue, $variables, $objectTypeFieldResolutionFeedbackStore);
            return;
        }
    }

    protected function getNoAliasFieldOutputKey(string $field): string
    {
        // GraphQL: Use fieldName only
        if (App::getState('only-fieldname-as-outputkey')) {
            return $this->getFieldName($field);
        }
        return parent::getNoAliasFieldOutputKey($field);
    }

    protected function serializeObject(object $object): string
    {
        return $this->getObjectSerializationManager()->serialize($object);
    }
}
