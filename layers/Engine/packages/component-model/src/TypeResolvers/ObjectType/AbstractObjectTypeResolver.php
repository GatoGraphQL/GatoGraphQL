<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ObjectType;

use Exception;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\Environment;
use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\Feedback\Tokens;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\Schema\FieldQueryUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaDefinitionTypes;
use PoP\ComponentModel\TypeResolvers\AbstractRelationalTypeResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Root\Environment as RootEnvironment;

abstract class AbstractObjectTypeResolver extends AbstractRelationalTypeResolver implements ObjectTypeResolverInterface
{
    /**
     * Cache of which objectTypeFieldResolvers will process the given field
     *
     * @var array<string, ObjectTypeFieldResolverInterface[]>
     */
    protected array $objectTypeFieldResolvers = [];
    /**
     * @var array<string, array>|null
     */
    protected ?array $mandatoryDirectivesForFields = null;
    /**
     * @var array<string, ObjectTypeFieldResolverInterface>|null
     */
    protected ?array $schemaObjectTypeFieldResolvers = null;
    /**
     * @var InterfaceTypeResolverInterface[]|null
     */
    protected ?array $interfaceTypeResolvers = null;
    /**
     * @var array<string, array>
     */
    private array $dissectedFieldForSchemaCache = [];
    /**
     * @var array<string, array>
     */
    private array $fieldNamesResolvedByObjectTypeFieldResolver = [];
    /**
     * @var InterfaceTypeFieldResolverInterface[]|null
     */
    protected ?array $interfaceTypeFieldResolvers = null;

    /**
     * Watch out! This function will be overridden for the UnionTypeResolver
     *
     * Collect all directives for all fields, and then build a single directive pipeline for all fields,
     * including all directives, even if they don't apply to all fields
     * Eg: id|title<skip>|excerpt<translate> will produce a pipeline [Skip, Translate] where they apply
     * to different fields. After producing the pipeline, add the mandatory items
     */
    final public function enqueueFillingObjectsFromIDs(array $ids_data_fields): void
    {
        $mandatoryDirectivesForFields = $this->getAllMandatoryDirectivesForFields();
        $mandatorySystemDirectives = $this->getMandatoryDirectives();
        foreach ($ids_data_fields as $id => $data_fields) {
            $fields = $this->getFieldsToEnqueueFillingObjectsFromIDs($data_fields);
            $this->doEnqueueFillingObjectsFromIDs($fields, $mandatoryDirectivesForFields, $mandatorySystemDirectives, $id, $data_fields);
        }
    }

    final public function getAllMandatoryDirectivesForFields(): array
    {
        if ($this->mandatoryDirectivesForFields === null) {
            $this->mandatoryDirectivesForFields = $this->calculateAllMandatoryDirectivesForFields();
        }
        return $this->mandatoryDirectivesForFields;
    }

    private function calculateAllMandatoryDirectivesForFields(): array
    {
        $mandatoryDirectivesForFields = [];
        $typeResolverDecorators = $this->getAllRelationalTypeResolverDecorators();
        foreach ($typeResolverDecorators as $typeResolverDecorator) {
            // array_merge_recursive so that if 2 different decorators add a directive for the same field, the results are merged together, not override each other
            if ($typeResolverDecorator->enabled($this)) {
                $mandatoryDirectivesForFields = array_merge_recursive(
                    $mandatoryDirectivesForFields,
                    $typeResolverDecorator->getMandatoryDirectivesForFields($this)
                );
            }
        }
        return $mandatoryDirectivesForFields;
    }

    /**
     * By default, do nothing
     *
     * @param array<string, mixed> $fieldArgs
     */
    public function validateFieldArgumentsForSchema(string $field, array $fieldArgs, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations): array
    {
        return $fieldArgs;
    }

    /**
     * @return array<string,mixed>|null `null` if there are no objectTypeFieldResolvers for the field
     */
    final public function getSchemaFieldArgs(string $field): ?array
    {
        // Get the value from a fieldResolver, from the first one that resolves it
        if ($objectTypeFieldResolvers = $this->getObjectTypeFieldResolversForField($field)) {
            $fieldName = $this->fieldQueryInterpreter->getFieldName($field);
            $fieldArgs = $this->fieldQueryInterpreter->extractStaticFieldArguments($field);
            $fieldSchemaDefinition = $objectTypeFieldResolvers[0]->getSchemaDefinitionForField($this, $fieldName, $fieldArgs);
            return $fieldSchemaDefinition[SchemaDefinition::ARGS] ?? [];
        }

        return null;
    }

    final public function enableOrderedSchemaFieldArgs(string $field): bool
    {
        // Get the value from a fieldResolver, from the first one that resolves it
        if ($objectTypeFieldResolvers = $this->getObjectTypeFieldResolversForField($field)) {
            $fieldName = $this->fieldQueryInterpreter->getFieldName($field);
            return $objectTypeFieldResolvers[0]->enableOrderedSchemaFieldArgs($this, $fieldName);
        }

        return false;
    }

    /**
     * @return array<string,mixed>|null `null` if there are no objectTypeFieldResolvers for the field
     */
    final public function getFieldSchemaDefinition(string $field): ?array
    {
        // Get the value from a fieldResolver, from the first one that resolves it
        if ($objectTypeFieldResolvers = $this->getObjectTypeFieldResolversForField($field)) {
            $fieldName = $this->fieldQueryInterpreter->getFieldName($field);
            $fieldArgs = $this->fieldQueryInterpreter->extractStaticFieldArguments($field);
            return $objectTypeFieldResolvers[0]->getSchemaDefinitionForField($this, $fieldName, $fieldArgs);
        }

        return null;
    }

    final public function resolveFieldValidationErrorDescriptions(string $field, array &$variables = null): array
    {
        // Get the value from a fieldResolver, from the first one that resolves it
        list(
            $validField,
            $fieldName,
            $fieldArgs,
            $schemaErrors,
        ) = $this->dissectFieldForSchema($field);
        // Dissecting the field may already fail, then already return the error
        if ($schemaErrors) {
            return $schemaErrors;
        }
        if ($objectTypeFieldResolvers = $this->getObjectTypeFieldResolversForField($field)) {
            if ($maybeErrors = $objectTypeFieldResolvers[0]->resolveFieldValidationErrorDescriptions($this, $fieldName, $fieldArgs)) {
                foreach ($maybeErrors as $error) {
                    $schemaErrors[] = [
                        Tokens::PATH => [$field],
                        Tokens::MESSAGE => $error,
                    ];
                }
            }
            return $schemaErrors;
        }

        // If we reach here, no fieldResolver processes this field, which is an error
        /**
         * If the error happened from requesting a version that doesn't exist, show an appropriate error message
         */
        if (
            Environment::enableSemanticVersionConstraints()
            && ($versionConstraint = $fieldArgs[SchemaDefinition::VERSION_CONSTRAINT] ?? null)
        ) {
            $errorMessage = sprintf(
                $this->translationAPI->__(
                    'There is no field \'%s\' on type \'%s\' satisfying version constraint \'%s\'',
                    'pop-component-model'
                ),
                $fieldName,
                $this->getMaybeNamespacedTypeName(),
                $versionConstraint,
            );
        } else {
            $errorMessage = sprintf(
                $this->translationAPI->__(
                    'There is no field \'%s\' on type \'%s\'',
                    'pop-component-model'
                ),
                $fieldName,
                $this->getMaybeNamespacedTypeName()
            );
        }
        return [
            [
                Tokens::PATH => [$field],
                Tokens::MESSAGE => $errorMessage,
            ],
        ];
    }

    final public function resolveFieldValidationWarningDescriptions(string $field, array &$variables = null): array
    {
        // Get the value from a fieldResolver, from the first one that resolves it
        if ($objectTypeFieldResolvers = $this->getObjectTypeFieldResolversForField($field)) {
            list(
                $validField,
                $fieldName,
                $fieldArgs,
                $schemaErrors,
                $schemaWarnings,
            ) = $this->dissectFieldForSchema($field);
            if ($maybeWarnings = $objectTypeFieldResolvers[0]->resolveFieldValidationWarningDescriptions($this, $fieldName, $fieldArgs)) {
                foreach ($maybeWarnings as $warning) {
                    $schemaWarnings[] = [
                        Tokens::PATH => [$field],
                        Tokens::MESSAGE => $warning,
                    ];
                }
            }
            return $schemaWarnings;
        }

        return [];
    }

    final public function resolveFieldDeprecationDescriptions(string $field, array &$variables = null): array
    {
        // Get the value from a fieldResolver, from the first one that resolves it
        if ($objectTypeFieldResolvers = $this->getObjectTypeFieldResolversForField($field)) {
            list(
                $validField,
                $fieldName,
                $fieldArgs,
                $schemaErrors,
                $schemaWarnings,
                $schemaDeprecations,
            ) = $this->dissectFieldForSchema($field);
            $fieldSchemaDefinition = $objectTypeFieldResolvers[0]->getSchemaDefinitionForField($this, $fieldName, $fieldArgs);
            if ($fieldSchemaDefinition[SchemaDefinition::DEPRECATED] ?? null) {
                $schemaDeprecations[] = [
                    Tokens::PATH => [$field],
                    Tokens::MESSAGE => $fieldSchemaDefinition[SchemaDefinition::DEPRECATIONDESCRIPTION],
                ];
            }
            // Check for deprecations in the enums
            if ($maybeDeprecations = $objectTypeFieldResolvers[0]->resolveFieldValidationDeprecationDescriptions($this, $fieldName, $fieldArgs)) {
                foreach ($maybeDeprecations as $deprecation) {
                    $schemaDeprecations[] = [
                        Tokens::PATH => [$field],
                        Tokens::MESSAGE => $deprecation,
                    ];
                }
            }
            return $schemaDeprecations;
        }

        return [];
    }

    final public function getFieldTypeResolver(string $field): ?ConcreteTypeResolverInterface
    {
        // Get the value from a fieldResolver, from the first one that resolves it
        if ($objectTypeFieldResolvers = $this->getObjectTypeFieldResolversForField($field)) {
            list(
                $validField,
                $fieldName,
            ) = $this->dissectFieldForSchema($field);
            return $objectTypeFieldResolvers[0]->getFieldTypeResolver($this, $fieldName);
        }

        return null;
    }

    final public function getFieldMutationResolver(string $field): ?MutationResolverInterface
    {
        // Get the value from a fieldResolver, from the first one that resolves it
        if ($objectTypeFieldResolvers = $this->getObjectTypeFieldResolversForField($field)) {
            list(
                $validField,
                $fieldName,
            ) = $this->dissectFieldForSchema($field);
            return $objectTypeFieldResolvers[0]->getFieldMutationResolver($this, $fieldName);
        }

        return null;
    }

    final public function isFieldAMutation(string $field): ?bool
    {
        // Get the value from a fieldResolver, from the first one that resolves it
        if ($objectTypeFieldResolvers = $this->getObjectTypeFieldResolversForField($field)) {
            list(
                $validField,
                $fieldName,
            ) = $this->dissectFieldForSchema($field);
            $fieldMutationResolver = $objectTypeFieldResolvers[0]->getFieldMutationResolver($this, $fieldName);
            return $fieldMutationResolver !== null;
        }

        return null;
    }

    final protected function dissectFieldForSchema(string $field): array
    {
        if (!isset($this->dissectedFieldForSchemaCache[$field])) {
            $this->dissectedFieldForSchemaCache[$field] = $this->doDissectFieldForSchema($field);
        }
        return $this->dissectedFieldForSchemaCache[$field];
    }

    private function doDissectFieldForSchema(string $field): array
    {
        return $this->fieldQueryInterpreter->extractFieldArgumentsForSchema($this, $field);
    }

    /**
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    final public function resolveValue(
        object $object,
        string $field,
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        // Get the value from a fieldResolver, from the first one who can deliver the value
        // (The fact that they resolve the fieldName doesn't mean that they will always resolve it for that specific $object)
        if ($objectTypeFieldResolvers = $this->getObjectTypeFieldResolversForField($field)) {
            // Important: $validField becomes $field: remove all invalid fieldArgs before executing `resolveValue` on the fieldResolver
            list(
                $field,
                $fieldName,
                $fieldArgs,
                $schemaErrors,
                $schemaWarnings,
            ) = $this->dissectFieldForSchema($field);

            // Store the warnings to be read if needed
            if ($schemaWarnings) {
                $this->feedbackMessageStore->addSchemaWarnings($schemaWarnings);
            }
            if ($schemaErrors) {
                return $this->errorProvider->getNestedSchemaErrorsFieldError($schemaErrors, $fieldName);
            }

            // Important: calculate 'isAnyFieldArgumentValueDynamic' before resolving the args for the object
            // That is because if when resolving there is an error, the fieldArgValue will be removed completely, then we don't know that we must validate the schema again
            // Eg: doing /?query=arrayUnique(extract(..., 0)) and extract fails, arrayUnique will have no fieldArgs. However its fieldArg is mandatory, but by then it doesn't know it needs to validate it
            // Before resolving the fieldArgValues which are fields:
            // Calculate $validateSchemaOnObject: if any value containes a field, then we must perform the schemaValidation on the item, such as checking that all mandatory fields are there
            // For instance: After resolving a field and being casted it may be incorrect, so the value is invalidated, and after the schemaValidation the proper error is shown
            // Also need to check for variables, since these must be resolved too
            // For instance: ?query=posts(limit:3),post(id:$id).id|title&id=112
            // We can also force it through an option. This is needed when the field is created on runtime.
            // Eg: through the <transform> directive, in which case no parameter is dynamic anymore by the time it reaches here, yet it was not validated statically either
            $validateSchemaOnObject =
                ($options[self::OPTION_VALIDATE_SCHEMA_ON_RESULT_ITEM] ?? null) ||
                FieldQueryUtils::isAnyFieldArgumentValueDynamic(
                    array_values(
                        $this->fieldQueryInterpreter->extractFieldArguments($this, $field) ?? []
                    )
                );

            // Once again, the $validField becomes the $field
            list(
                $field,
                $fieldName,
                $fieldArgs,
                $objectErrors,
                $objectWarnings
            ) = $this->fieldQueryInterpreter->extractFieldArgumentsForObject($this, $object, $field, $variables, $expressions);

            // Store the warnings to be read if needed
            if ($objectWarnings) {
                $this->feedbackMessageStore->addObjectWarnings($objectWarnings);
            }
            if ($objectErrors) {
                return $this->errorProvider->getNestedObjectErrorsFieldError($objectErrors, $fieldName);
            }

            foreach ($objectTypeFieldResolvers as $objectTypeFieldResolver) {
                // Also send the typeResolver along, as to get the id of the $object being passed
                if ($objectTypeFieldResolver->resolveCanProcessObject($this, $object, $fieldName, $fieldArgs)) {
                    if ($validateSchemaOnObject) {
                        if ($maybeErrors = $objectTypeFieldResolver->resolveFieldValidationErrorDescriptions($this, $fieldName, $fieldArgs)) {
                            return $this->errorProvider->getValidationFailedError($fieldName, $fieldArgs, $maybeErrors);
                        }
                        if ($maybeDeprecations = $objectTypeFieldResolver->resolveFieldValidationDeprecationDescriptions($this, $fieldName, $fieldArgs)) {
                            $id = $this->getID($object);
                            foreach ($maybeDeprecations as $deprecation) {
                                $objectDeprecations[(string)$id][] = [
                                    Tokens::PATH => [$field],
                                    Tokens::MESSAGE => $deprecation,
                                ];
                            }
                            $this->feedbackMessageStore->addObjectDeprecations($objectDeprecations);
                        }
                    }
                    if ($validationErrorDescriptions = $objectTypeFieldResolver->getValidationErrorDescriptions($this, $object, $fieldName, $fieldArgs)) {
                        return $this->errorProvider->getValidationFailedError($fieldName, $fieldArgs, $validationErrorDescriptions);
                    }

                    // Resolve the value. If the field resolver throws an Exception,
                    // catch it and return the equivalent GraphQL error so that it
                    // fails gracefully in production (but not on development!)
                    try {
                        $value = $objectTypeFieldResolver->resolveValue($this, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
                    } catch (Exception $e) {
                        if (RootEnvironment::isApplicationEnvironmentDev()) {
                            throw $e;
                        }
                        return new Error(
                            'exception',
                            sprintf(
                                $this->translationAPI->__('Resolving field \'%s\' produced an exception, with message: \'%s\'. Please contact the admin.', 'component-model'),
                                $field,
                                $e->getMessage()
                            )
                        );
                    }

                    /**
                     * Validate that the value is what was defined in the schema, or throw a corresponding error.
                     *
                     * Items being validated:
                     *
                     * - Is it null?
                     * - Is it an array when it should be?
                     * - Is it not an array when it should not be?
                     *
                     * Items NOT being validated:
                     *
                     * - Is the returned type (String, Int, some Object, etc) the expected one?
                     *
                     * According to the GraphQL speck, checking if a non-null field returns null
                     * is handled always:
                     *
                     *   If the result of resolving a field is null (either because the function
                     *   to resolve the field returned null or because a field error was raised),
                     *   and that field is of a Non-Null type, then a field error is raised.
                     *   The error must be added to the "errors" list in the response.
                     *
                     * @see https://spec.graphql.org/draft/#sec-Handling-Field-Errors
                     *
                     * All other conditions, check them when enabled by configuration.
                     */
                    if ($value === null) {
                        $fieldSchemaDefinition = $objectTypeFieldResolver->getSchemaDefinitionForField($this, $fieldName, $fieldArgs);
                        if ($fieldSchemaDefinition[SchemaDefinition::NON_NULLABLE] ?? false) {
                            return $this->errorProvider->getNonNullableFieldError($fieldName);
                        }
                    } elseif (ComponentConfiguration::validateFieldTypeResponseWithSchemaDefinition()) {
                        $fieldSchemaDefinition = $objectTypeFieldResolver->getSchemaDefinitionForField($this, $fieldName, $fieldArgs);
                        // If may be array or not, then there's no validation to do
                        $fieldTypeName = $fieldSchemaDefinition[SchemaDefinition::TYPE_NAME];
                        $fieldMayBeArrayType = in_array($fieldTypeName, [
                            SchemaDefinitionTypes::TYPE_INPUT_OBJECT,
                            SchemaDefinitionTypes::TYPE_OBJECT,
                            SchemaDefinitionTypes::TYPE_MIXED,
                        ]);
                        if (!$fieldMayBeArrayType) {
                            $fieldIsArrayType = $fieldSchemaDefinition[SchemaDefinition::IS_ARRAY] ?? false;
                            if (
                                !$fieldIsArrayType
                                && is_array($value)
                            ) {
                                return $this->errorProvider->getMustNotBeArrayFieldError($fieldName, $value);
                            }
                            if (
                                $fieldIsArrayType
                                && !is_array($value)
                            ) {
                                return $this->errorProvider->getMustBeArrayFieldError($fieldName, $value);
                            }
                            $fieldIsNonNullArrayItemsType = $fieldSchemaDefinition[SchemaDefinition::IS_NON_NULLABLE_ITEMS_IN_ARRAY] ?? false;
                            if (
                                $fieldIsNonNullArrayItemsType
                                && is_array($value)
                                && array_filter(
                                    $value,
                                    fn (mixed $arrayItem) => $arrayItem === null
                                )
                            ) {
                                return $this->errorProvider->getArrayMustNotHaveNullItemsFieldError($fieldName, $value);
                            }
                            $fieldIsArrayOfArraysType = $fieldSchemaDefinition[SchemaDefinition::IS_ARRAY_OF_ARRAYS] ?? false;
                            if (
                                !$fieldIsArrayOfArraysType
                                && is_array($value)
                                && array_filter(
                                    $value,
                                    fn (mixed $arrayItem) => is_array($arrayItem)
                                )
                            ) {
                                return $this->errorProvider->getMustNotBeArrayOfArraysFieldError($fieldName, $value);
                            }
                            if (
                                $fieldIsArrayOfArraysType
                                && is_array($value)
                                && array_filter(
                                    $value,
                                    // `null` could be accepted as an array! (Validation against null comes next)
                                    fn (mixed $arrayItem) => !is_array($arrayItem) && $arrayItem !== null
                                )
                            ) {
                                return $this->errorProvider->getMustBeArrayOfArraysFieldError($fieldName, $value);
                            }
                            $fieldIsNonNullArrayOfArraysItemsType = $fieldSchemaDefinition[SchemaDefinition::IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS] ?? false;
                            if (
                                $fieldIsNonNullArrayOfArraysItemsType
                                && is_array($value)
                                && array_filter(
                                    $value,
                                    fn (?array $arrayItem) => $arrayItem === null ? false : array_filter(
                                        $arrayItem,
                                        fn (mixed $arrayItemItem) => $arrayItemItem === null
                                    ) !== [],
                                )
                            ) {
                                return $this->errorProvider->getArrayOfArraysMustNotHaveNullItemsFieldError($fieldName, $value);
                            }
                        }
                    }

                    // Everything is good, return the value (which could also be an Error!)
                    return $value;
                }
            }
            return $this->errorProvider->getNoObjectTypeFieldResolverProcessesFieldError($this->getID($object), $fieldName, $fieldArgs);
        }

        // Return an error to indicate that no fieldResolver processes this field, which is different than returning a null value.
        // Needed for compatibility with CustomPostUnionTypeResolver (so that data-fields aimed for another post_type are not retrieved)
        $fieldName = $this->fieldQueryInterpreter->getFieldName($field);
        return $this->errorProvider->getNoFieldError($this->getID($object), $fieldName, $this->getMaybeNamespacedTypeName());
    }

    final protected function getSchemaObjecTypeObjectTypeFieldResolvers(bool $global): array
    {
        $schemaObjectTypeFieldResolvers = [];
        foreach ($this->getAllObjectTypeFieldResolvers() as $fieldName => $objectTypeFieldResolvers) {
            // Get the documentation from the first element
            $objectTypeFieldResolver = $objectTypeFieldResolvers[0];
            $isGlobal = $objectTypeFieldResolver->isGlobal($this, $fieldName);
            if (($global && $isGlobal) || (!$global && !$isGlobal)) {
                $schemaObjectTypeFieldResolvers[$fieldName] =  $objectTypeFieldResolver;
            }
        }
        return $schemaObjectTypeFieldResolvers;
    }

    protected function addSchemaDefinition(array $stackMessages, array &$generalMessages, array $options = []): void
    {
        parent::addSchemaDefinition($stackMessages, $generalMessages, $options);

        $typeSchemaKey = $this->schemaDefinitionService->getTypeSchemaKey($this);
        $typeName = $this->getMaybeNamespacedTypeName();

        // Add the directives (non-global)
        $this->schemaDefinition[$typeSchemaKey][SchemaDefinition::DIRECTIVES] = [];
        $schemaDirectiveResolvers = $this->getSchemaDirectiveResolvers(false);
        foreach ($schemaDirectiveResolvers as $directiveName => $directiveResolver) {
            $this->schemaDefinition[$typeSchemaKey][SchemaDefinition::DIRECTIVES][$directiveName] = $this->getDirectiveSchemaDefinition($directiveResolver, $options);
        }

        // Add the fields (non-global)
        $this->schemaDefinition[$typeSchemaKey][SchemaDefinition::FIELDS] = [];
        $schemaObjectTypeFieldResolvers = $this->getSchemaObjecTypeObjectTypeFieldResolvers(false);
        foreach ($schemaObjectTypeFieldResolvers as $fieldName => $objectTypeFieldResolver) {
            $this->addFieldSchemaDefinition($objectTypeFieldResolver, $fieldName, $stackMessages, $generalMessages, $options);
        }

        // Add all the implemented interfaces
        $typeInterfaceDefinitions = [];
        foreach ($this->getAllImplementedInterfaceTypeResolvers() as $interfaceTypeResolver) {
            $interfaceSchemaKey = $this->schemaDefinitionService->getTypeSchemaKey($interfaceTypeResolver);

            // Conveniently get the fields from the schema, which have already been calculated above
            // since they also include their interface fields
            $interfaceFieldNames = $interfaceTypeResolver->getFieldNamesToImplement();
            // The Interface fields may be implemented as either ObjectTypeFieldResolver fields or ObjectTypeFieldResolver connections,
            // Eg: Interface "Elemental" has field "id" and connection "self"
            // Merge both cases into interface fields
            $interfaceFields = array_filter(
                $this->schemaDefinition[$typeSchemaKey][SchemaDefinition::FIELDS],
                function ($fieldName) use ($interfaceFieldNames) {
                    return in_array($fieldName, $interfaceFieldNames);
                },
                ARRAY_FILTER_USE_KEY
            );
            $interfaceConnections = array_filter(
                $this->schemaDefinition[$typeSchemaKey][SchemaDefinition::CONNECTIONS],
                function ($connectionName) use ($interfaceFieldNames) {
                    return in_array($connectionName, $interfaceFieldNames);
                },
                ARRAY_FILTER_USE_KEY
            );
            $interfaceFields = array_merge(
                $interfaceFields,
                $interfaceConnections
            );
            // An interface can itself implement interfaces!
            $interfaceImplementedInterfaceNames = [];
            foreach ($interfaceTypeResolver->getPartiallyImplementedInterfaceTypeResolvers() as $implementedInterfaceTypeResolver) {
                $interfaceImplementedInterfaceNames[] = $implementedInterfaceTypeResolver->getMaybeNamespacedTypeName();
            }
            $interfaceName = $interfaceTypeResolver->getMaybeNamespacedTypeName();
            // Possible types: Because we are generating this list as we go along resolving all the types, simply have this value point to a reference in $generalMessages
            // Just by updating that variable, it will eventually be updated everywhere
            $generalMessages['interfaceGeneralTypes'][$interfaceName] = $generalMessages['interfaceGeneralTypes'][$interfaceName] ?? [];
            $interfacePossibleTypes = &$generalMessages['interfaceGeneralTypes'][$interfaceName];
            // Add this type to the list of implemented types for this interface
            $interfacePossibleTypes[] = $typeName;
            $typeInterfaceDefinitions[$interfaceSchemaKey] = [
                SchemaDefinition::NAME => $interfaceName,
                SchemaDefinition::NAMESPACED_NAME => $interfaceTypeResolver->getNamespacedTypeName(),
                SchemaDefinition::ELEMENT_NAME => $interfaceTypeResolver->getTypeName(),
                SchemaDefinition::DESCRIPTION => $interfaceTypeResolver->getTypeDescription(),
                SchemaDefinition::FIELDS => $interfaceFields,
                SchemaDefinition::INTERFACES => $interfaceImplementedInterfaceNames,
                // The list of types that implement this interface
                SchemaDefinition::POSSIBLE_TYPES => &$interfacePossibleTypes,
            ];
        }
        $this->schemaDefinition[$typeSchemaKey][SchemaDefinition::INTERFACES] = $typeInterfaceDefinitions;
    }

    final protected function addFieldSchemaDefinition(ObjectTypeFieldResolverInterface $objectTypeFieldResolver, string $fieldName, array $stackMessages, array &$generalMessages, array $options = []): void
    {
        /**
         * Fields may not be directly visible in the schema
         */
        if ($objectTypeFieldResolver->skipAddingToSchemaDefinition($this, $fieldName)) {
            return;
        }

        // Watch out! We are passing empty $fieldArgs to generate the schema!
        $fieldSchemaDefinition = $objectTypeFieldResolver->getSchemaDefinitionForField($this, $fieldName, []);
        // Add subfield schema if it is deep, and this typeResolver has not been processed yet
        if ($options['deep'] ?? null) {
            // If this field is relational, then add its own schema
            $fieldTypeResolver = $this->getFieldTypeResolver($fieldName);
            if ($fieldTypeResolver instanceof RelationalTypeResolverInterface) {
                $fieldSchemaDefinition[SchemaDefinition::TYPE_SCHEMA] = $fieldTypeResolver->getSchemaDefinition($stackMessages, $generalMessages, $options);
            }
        }
        // Convert the field type from its internal representation (eg: "array:id") to the type (eg: "array:Post")
        if (!($options['useTypeName'] ?? null) && ($types = $fieldSchemaDefinition[SchemaDefinition::TYPE_SCHEMA] ?? null)) {
            // Display the type under entry "referencedType"
            $typeNames = array_keys($types);
            $fieldSchemaDefinition[SchemaDefinition::REFERENCED_TYPE] = $typeNames[0];
        }
        $isGlobal = $objectTypeFieldResolver->isGlobal($this, $fieldName);
        $isConnection = isset($fieldSchemaDefinition[SchemaDefinition::RELATIONAL]) && $fieldSchemaDefinition[SchemaDefinition::RELATIONAL];
        if ($isGlobal) {
            // If it is relational, it is a global connection
            if ($isConnection) {
                $entry = SchemaDefinition::GLOBAL_CONNECTIONS;
                // Remove the "types"
                if ($options['useTypeName'] ?? null) {
                    unset($fieldSchemaDefinition[SchemaDefinition::TYPE_SCHEMA]);
                }
            } else {
                $entry = SchemaDefinition::GLOBAL_FIELDS;
            }
        } else {
            // Split the results into "fields" and "connections"
            $entry = $isConnection ?
                SchemaDefinition::CONNECTIONS :
                SchemaDefinition::FIELDS;
        }
        // Can remove attribute "relational"
        if ($isConnection) {
            unset($fieldSchemaDefinition[SchemaDefinition::RELATIONAL]);
        }
        $typeSchemaKey = $this->schemaDefinitionService->getTypeSchemaKey($this);
        $this->schemaDefinition[$typeSchemaKey][$entry][$fieldName] = $fieldSchemaDefinition;
    }

    /**
     * Return the fieldNames resolved by the fieldResolver, adding a hook to disable each of them (eg: to implement a private schema)
     *
     * @return string[]
     */
    final protected function getFieldNamesResolvedByObjectTypeFieldResolver(ObjectTypeFieldResolverInterface $objectTypeFieldResolver): array
    {
        $objectTypeFieldResolverClass = get_class($objectTypeFieldResolver);
        if (!isset($this->fieldNamesResolvedByObjectTypeFieldResolver[$objectTypeFieldResolverClass])) {
            // Merge the fieldNames resolved by this field resolver class, and the interfaces it implements
            $fieldNames = array_values(array_unique(array_merge(
                $objectTypeFieldResolver->getFieldNamesToResolve(),
                $objectTypeFieldResolver->getFieldNamesFromInterfaces()
            )));
            $fieldNames = $this->maybeExcludeFieldNamesFromSchema(
                $this,
                $objectTypeFieldResolver,
                $fieldNames
            );
            $this->fieldNamesResolvedByObjectTypeFieldResolver[$objectTypeFieldResolverClass] = $fieldNames;
        }
        return $this->fieldNamesResolvedByObjectTypeFieldResolver[$objectTypeFieldResolverClass];
    }

    final protected function getAllObjectTypeFieldResolvers(): array
    {
        if ($this->schemaObjectTypeFieldResolvers === null) {
            $this->schemaObjectTypeFieldResolvers = $this->calculateAllObjectTypeFieldResolvers();
        }
        return $this->schemaObjectTypeFieldResolvers;
    }

    private function calculateAllObjectTypeFieldResolvers(): array
    {
        $schemaObjectTypeFieldResolvers = [];

        // Get the ObjectTypeFieldResolvers attached to this ObjectTypeResolver
        $class = get_class($this->getTypeResolverToCalculateSchema());
        // Iterate classes from the current class towards the parent classes until finding typeResolver that satisfies processing this field
        do {
            /** @var ObjectTypeFieldResolverInterface[] */
            $attachedObjectTypeFieldResolvers = $this->attachableExtensionManager->getAttachedExtensions($class, AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS);
            foreach ($attachedObjectTypeFieldResolvers as $objectTypeFieldResolver) {
                // Process the fields which have not been processed yet
                $extensionFieldNames = $this->getFieldNamesResolvedByObjectTypeFieldResolver($objectTypeFieldResolver);
                foreach (array_diff($extensionFieldNames, array_keys($schemaObjectTypeFieldResolvers)) as $fieldName) {
                    // Watch out here: no fieldArgs!!!! So this deals with the base case (static), not with all cases (runtime)
                    // If using an ACL to remove a field from an interface,
                    // getting the ObjectTypeFieldResolvers for that field will be empty
                    // Then ignore adding the field, it must not be added to the schema
                    if ($objectTypeFieldResolversForField = $this->getObjectTypeFieldResolversForField($fieldName)) {
                        $schemaObjectTypeFieldResolvers[$fieldName] = $objectTypeFieldResolversForField;
                    }
                }
            }
            // Otherwise, continue iterating for the class parents
        } while ($class = get_parent_class($class));

        return $schemaObjectTypeFieldResolvers;
    }

    /**
     * @return InterfaceTypeFieldResolverInterface[]
     */
    final protected function getAllImplementedInterfaceTypeFieldResolvers(): array
    {
        if ($this->interfaceTypeFieldResolvers === null) {
            $this->interfaceTypeFieldResolvers = $this->calculateAllImplementedInterfaceTypeFieldResolvers();
        }
        return $this->interfaceTypeFieldResolvers;
    }

    /**
     * @return InterfaceTypeFieldResolverInterface[]
     */
    private function calculateAllImplementedInterfaceTypeFieldResolvers(): array
    {
        $interfaceTypeFieldResolvers = [];
        $processedObjectTypeFieldResolverClasses = [];
        foreach ($this->getAllObjectTypeFieldResolvers() as $fieldName => $objectTypeFieldResolvers) {
            foreach ($objectTypeFieldResolvers as $objectTypeFieldResolver) {
                $objectTypeFieldResolverClass = get_class($objectTypeFieldResolver);
                if (!in_array($objectTypeFieldResolverClass, $processedObjectTypeFieldResolverClasses)) {
                    $processedObjectTypeFieldResolverClasses[] = $objectTypeFieldResolverClass;
                    foreach ($objectTypeFieldResolver->getImplementedInterfaceTypeFieldResolvers() as $implementedInterfaceTypeFieldResolver) {
                        // Add under class as to mimick `array_unique` for object
                        $interfaceTypeFieldResolvers[get_class($implementedInterfaceTypeFieldResolver)] = $implementedInterfaceTypeFieldResolver;
                    }
                }
            }
        }
        return array_values($interfaceTypeFieldResolvers);
    }

    /**
     * @return InterfaceTypeResolverInterface[]
     */
    final public function getAllImplementedInterfaceTypeResolvers(): array
    {
        if ($this->interfaceTypeResolvers === null) {
            $this->interfaceTypeResolvers = $this->calculateAllImplementedInterfaceTypeResolvers();
        }
        return $this->interfaceTypeResolvers;
    }

    /**
     * @return InterfaceTypeResolverInterface[]
     */
    private function calculateAllImplementedInterfaceTypeResolvers(): array
    {
        $interfaceTypeResolvers = [];
        foreach ($this->getAllImplementedInterfaceTypeFieldResolvers() as $interfaceTypeFieldResolver) {
            foreach ($interfaceTypeFieldResolver->getPartiallyImplementedInterfaceTypeResolvers() as $partiallyImplementedInterfaceTypeResolver) {
                // Add under class as to mimick `array_unique` for object
                $interfaceTypeResolvers[get_class($partiallyImplementedInterfaceTypeResolver)] = $partiallyImplementedInterfaceTypeResolver;
            }
        }
        $interfaceTypeResolvers = array_values($interfaceTypeResolvers);
        // Every InterfaceTypeResolver can be injected fields from many InterfaceTypeFieldResolvers
        // Make sure that this typeResolver implements all these InterfaceTypeFieldResolver
        // If not, the type does not fully satisfy the Interface
        $implementedInterfaceTypeFieldResolvers = $this->getAllImplementedInterfaceTypeFieldResolvers();
        return array_filter(
            $interfaceTypeResolvers,
            fn (InterfaceTypeResolverInterface $interfaceTypeResolver) => array_udiff(
                $interfaceTypeResolver->getAllInterfaceTypeFieldResolvers(),
                $implementedInterfaceTypeFieldResolvers,
                fn (object $a, object $b) => get_class($a) <=> get_class($b)
            ) === [],
        );
    }

    /**
     * @return ObjectTypeFieldResolverInterface[]
     */
    final protected function getObjectTypeFieldResolversForField(string $field): array
    {
        // Calculate the fieldResolver to process this field if not already in the cache
        // If none is found, this value will be set to NULL. This is needed to stop attempting to find the fieldResolver
        if (!isset($this->objectTypeFieldResolvers[$field])) {
            $this->objectTypeFieldResolvers[$field] = $this->calculateObjectTypeFieldResolversForField($field);
        }

        return $this->objectTypeFieldResolvers[$field];
    }

    final public function hasObjectTypeFieldResolversForField(string $field): bool
    {
        return !empty($this->getObjectTypeFieldResolversForField($field));
    }

    private function calculateObjectTypeFieldResolversForField(string $field): array
    {
        // Important: here we CAN'T use `dissectFieldForSchema` to get the fieldArgs, because it will attempt to validate them
        // To validate them, the fieldQueryInterpreter needs to know the schema, so it once again calls functions from this typeResolver
        // Generating an infinite loop
        // Then, just to find out which ObjectTypeFieldResolvers will process this field, crudely obtain the fieldArgs, with NO schema-based validation!
        // list(
        //     $field,
        //     $fieldName,
        //     $fieldArgs,
        // ) = $this->dissectFieldForSchema($field);
        $fieldName = $this->fieldQueryInterpreter->getFieldName($field);
        $fieldArgs = $this->fieldQueryInterpreter->extractStaticFieldArguments($field);

        $objectTypeFieldResolvers = [];
        // Get the ObjectTypeFieldResolvers attached to this ObjectTypeResolver
        $class = get_class($this->getTypeResolverToCalculateSchema());
        // Iterate classes from the current class towards the parent classes until finding typeResolver that satisfies processing this field
        do {
            // All the Units and their priorities for this class level
            $classTypeResolverPriorities = [];
            $classObjectTypeFieldResolvers = [];

            // Important: do array_reverse to enable more specific hooks, which are initialized later on in the project, to be the chosen ones (if their priority is the same)
            /** @var ObjectTypeFieldResolverInterface[] */
            $attachedObjectTypeFieldResolvers = array_reverse($this->attachableExtensionManager->getAttachedExtensions($class, AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS));
            foreach ($attachedObjectTypeFieldResolvers as $objectTypeFieldResolver) {
                $extensionFieldNames = $this->getFieldNamesResolvedByObjectTypeFieldResolver($objectTypeFieldResolver);
                if (in_array($fieldName, $extensionFieldNames)) {
                    // Check that the fieldResolver can handle the field based on other parameters (eg: "version" in the fieldArgs)
                    if ($objectTypeFieldResolver->resolveCanProcess($this, $fieldName, $fieldArgs)) {
                        $extensionPriority = $objectTypeFieldResolver->getPriorityToAttachToClasses();
                        $classTypeResolverPriorities[] = $extensionPriority;
                        $classObjectTypeFieldResolvers[] = $objectTypeFieldResolver;
                    }
                }
            }
            // Sort the found units by their priority, and then add to the stack of all units, for all classes
            // Higher priority means they execute first!
            array_multisort($classTypeResolverPriorities, SORT_DESC, SORT_NUMERIC, $classObjectTypeFieldResolvers);
            // Add under class as to mimick `array_unique` for object
            foreach ($classObjectTypeFieldResolvers as $classObjectTypeFieldResolver) {
                $objectTypeFieldResolvers[get_class($classObjectTypeFieldResolver)] = $classObjectTypeFieldResolver;
            }
            // Continue iterating for the class parents
        } while ($class = get_parent_class($class));

        // Return all the units that resolve the fieldName
        return array_values($objectTypeFieldResolvers);
    }

    private function calculateFieldNamesToResolve(): array
    {
        $fieldNames = [];

        // Iterate classes from the current class towards the parent classes until finding typeResolver that satisfies processing this field
        $class = get_class($this->getTypeResolverToCalculateSchema());
        do {
            /** @var ObjectTypeFieldResolverInterface[] */
            $attachedObjectTypeFieldResolvers = $this->attachableExtensionManager->getAttachedExtensions($class, AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS);
            foreach ($attachedObjectTypeFieldResolvers as $objectTypeFieldResolver) {
                $extensionFieldNames = $this->getFieldNamesResolvedByObjectTypeFieldResolver($objectTypeFieldResolver);
                $fieldNames = array_merge(
                    $fieldNames,
                    $extensionFieldNames
                );
            }
            // Continue iterating for the class parents
        } while ($class = get_parent_class($class));

        return array_values(array_unique($fieldNames));
    }
}
