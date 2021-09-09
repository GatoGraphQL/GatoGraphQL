<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\Object;

use Exception;
use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\Environment;
use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\Feedback\Tokens;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;
use PoP\ComponentModel\Schema\FieldQueryUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaHelpers;
use PoP\ComponentModel\TypeResolvers\AbstractRelationalTypeResolver;

abstract class AbstractObjectTypeResolver extends AbstractRelationalTypeResolver implements ObjectTypeResolverInterface
{
    public function getSelfFieldTypeResolverClass(): string
    {
        return get_called_class();
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
     * @return array<string,mixed>|null `null` if there are no fieldResolvers for the field
     */
    public function getSchemaFieldArgs(string $field): ?array
    {
        // Get the value from a fieldResolver, from the first one that resolves it
        if ($fieldResolvers = $this->getFieldResolversForField($field)) {
            $fieldName = $this->fieldQueryInterpreter->getFieldName($field);
            $fieldArgs = $this->fieldQueryInterpreter->extractStaticFieldArguments($field);
            $fieldSchemaDefinition = $fieldResolvers[0]->getSchemaDefinitionForField($this, $fieldName, $fieldArgs);
            return $fieldSchemaDefinition[SchemaDefinition::ARGNAME_ARGS] ?? [];
        }

        return null;
    }

    public function enableOrderedSchemaFieldArgs(string $field): bool
    {
        // Get the value from a fieldResolver, from the first one that resolves it
        if ($fieldResolvers = $this->getFieldResolversForField($field)) {
            $fieldName = $this->fieldQueryInterpreter->getFieldName($field);
            return $fieldResolvers[0]->enableOrderedSchemaFieldArgs($this, $fieldName);
        }

        return false;
    }

    public function resolveSchemaValidationErrorDescriptions(string $field, array &$variables = null): array
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
        if ($fieldResolvers = $this->getFieldResolversForField($field)) {
            if ($maybeErrors = $fieldResolvers[0]->resolveSchemaValidationErrorDescriptions($this, $fieldName, $fieldArgs)) {
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
            && ($versionConstraint = $fieldArgs[SchemaDefinition::ARGNAME_VERSION_CONSTRAINT] ?? null)
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

    public function resolveSchemaValidationWarningDescriptions(string $field, array &$variables = null): array
    {
        // Get the value from a fieldResolver, from the first one that resolves it
        if ($fieldResolvers = $this->getFieldResolversForField($field)) {
            list(
                $validField,
                $fieldName,
                $fieldArgs,
                $schemaErrors,
                $schemaWarnings,
            ) = $this->dissectFieldForSchema($field);
            if ($maybeWarnings = $fieldResolvers[0]->resolveSchemaValidationWarningDescriptions($this, $fieldName, $fieldArgs)) {
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

    public function resolveSchemaDeprecationDescriptions(string $field, array &$variables = null): array
    {
        // Get the value from a fieldResolver, from the first one that resolves it
        if ($fieldResolvers = $this->getFieldResolversForField($field)) {
            list(
                $validField,
                $fieldName,
                $fieldArgs,
                $schemaErrors,
                $schemaWarnings,
                $schemaDeprecations,
            ) = $this->dissectFieldForSchema($field);
            $fieldSchemaDefinition = $fieldResolvers[0]->getSchemaDefinitionForField($this, $fieldName, $fieldArgs);
            if ($fieldSchemaDefinition[SchemaDefinition::ARGNAME_DEPRECATED] ?? null) {
                $schemaDeprecations[] = [
                    Tokens::PATH => [$field],
                    Tokens::MESSAGE => $fieldSchemaDefinition[SchemaDefinition::ARGNAME_DEPRECATIONDESCRIPTION],
                ];
            }
            // Check for deprecations in the enums
            if ($maybeDeprecations = $fieldResolvers[0]->resolveSchemaValidationDeprecationDescriptions($this, $fieldName, $fieldArgs)) {
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

    public function getFieldTypeResolverClass(string $field): ?string
    {
        // Get the value from a fieldResolver, from the first one that resolves it
        if ($fieldResolvers = $this->getFieldResolversForField($field)) {
            list(
                $validField,
                $fieldName,
            ) = $this->dissectFieldForSchema($field);
            return $fieldResolvers[0]->getFieldTypeResolverClass($this, $fieldName);
        }

        return null;
    }

    protected function dissectFieldForSchema(string $field): ?array
    {
        if (!isset($this->dissectedFieldForSchemaCache[$field])) {
            $this->dissectedFieldForSchemaCache[$field] = $this->doDissectFieldForSchema($field);
        }
        return $this->dissectedFieldForSchemaCache[$field];
    }

    protected function doDissectFieldForSchema(string $field): ?array
    {
        return $this->fieldQueryInterpreter->extractFieldArgumentsForSchema($this, $field);
    }

    /**
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        object $resultItem,
        string $field,
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        // Get the value from a fieldResolver, from the first one who can deliver the value
        // (The fact that they resolve the fieldName doesn't mean that they will always resolve it for that specific $resultItem)
        if ($fieldResolvers = $this->getFieldResolversForField($field)) {
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

            // Important: calculate 'isAnyFieldArgumentValueDynamic' before resolving the args for the resultItem
            // That is because if when resolving there is an error, the fieldArgValue will be removed completely, then we don't know that we must validate the schema again
            // Eg: doing /?query=arrayUnique(extract(..., 0)) and extract fails, arrayUnique will have no fieldArgs. However its fieldArg is mandatory, but by then it doesn't know it needs to validate it
            // Before resolving the fieldArgValues which are fields:
            // Calculate $validateSchemaOnResultItem: if any value containes a field, then we must perform the schemaValidation on the item, such as checking that all mandatory fields are there
            // For instance: After resolving a field and being casted it may be incorrect, so the value is invalidated, and after the schemaValidation the proper error is shown
            // Also need to check for variables, since these must be resolved too
            // For instance: ?query=posts(limit:3),post(id:$id).id|title&id=112
            // We can also force it through an option. This is needed when the field is created on runtime.
            // Eg: through the <transform> directive, in which case no parameter is dynamic anymore by the time it reaches here, yet it was not validated statically either
            $validateSchemaOnResultItem =
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
                $dbErrors,
                $dbWarnings
            ) = $this->fieldQueryInterpreter->extractFieldArgumentsForResultItem($this, $resultItem, $field, $variables, $expressions);

            // Store the warnings to be read if needed
            if ($dbWarnings) {
                $this->feedbackMessageStore->addDBWarnings($dbWarnings);
            }
            if ($dbErrors) {
                return $this->errorProvider->getNestedDBErrorsFieldError($dbErrors, $fieldName);
            }

            foreach ($fieldResolvers as $fieldResolver) {
                // Also send the typeResolver along, as to get the id of the $resultItem being passed
                if ($fieldResolver->resolveCanProcessResultItem($this, $resultItem, $fieldName, $fieldArgs)) {
                    if ($validateSchemaOnResultItem) {
                        if ($maybeErrors = $fieldResolver->resolveSchemaValidationErrorDescriptions($this, $fieldName, $fieldArgs)) {
                            return $this->errorProvider->getValidationFailedError($fieldName, $fieldArgs, $maybeErrors);
                        }
                        if ($maybeDeprecations = $fieldResolver->resolveSchemaValidationDeprecationDescriptions($this, $fieldName, $fieldArgs)) {
                            $id = $this->getID($resultItem);
                            foreach ($maybeDeprecations as $deprecation) {
                                $dbDeprecations[(string)$id][] = [
                                    Tokens::PATH => [$field],
                                    Tokens::MESSAGE => $deprecation,
                                ];
                            }
                            $this->feedbackMessageStore->addDBDeprecations($dbDeprecations);
                        }
                    }
                    if ($validationErrorDescriptions = $fieldResolver->getValidationErrorDescriptions($this, $resultItem, $fieldName, $fieldArgs)) {
                        return $this->errorProvider->getValidationFailedError($fieldName, $fieldArgs, $validationErrorDescriptions);
                    }

                    // Resolve the value. If the field resolver throws an Exception,
                    // catch it and return the equivalent GraphQL error
                    try {
                        $value = $fieldResolver->resolveValue($this, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
                    } catch (Exception $e) {
                        return new Error(
                            'exception',
                            sprintf(
                                $this->translationAPI->__('Resolving field \'%s\' produced an exception, with message: \'%s\'', 'component-model'),
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
                        $fieldSchemaDefinition = $fieldResolver->getSchemaDefinitionForField($this, $fieldName, $fieldArgs);
                        if ($fieldSchemaDefinition[SchemaDefinition::ARGNAME_NON_NULLABLE] ?? false) {
                            return $this->errorProvider->getNonNullableFieldError($fieldName);
                        }
                    } elseif (ComponentConfiguration::validateFieldTypeResponseWithSchemaDefinition()) {
                        $fieldSchemaDefinition = $fieldResolver->getSchemaDefinitionForField($this, $fieldName, $fieldArgs);
                        // If may be array or not, then there's no validation to do
                        $fieldType = $fieldSchemaDefinition[SchemaDefinition::ARGNAME_TYPE];
                        $fieldMayBeArrayType = in_array($fieldType, [
                            SchemaDefinition::TYPE_INPUT_OBJECT,
                            SchemaDefinition::TYPE_OBJECT,
                            SchemaDefinition::TYPE_MIXED,
                        ]);
                        if (!$fieldMayBeArrayType) {
                            $fieldIsArrayType = $fieldSchemaDefinition[SchemaDefinition::ARGNAME_IS_ARRAY] ?? false;
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
                            $fieldIsNonNullArrayItemsType = $fieldSchemaDefinition[SchemaDefinition::ARGNAME_IS_NON_NULLABLE_ITEMS_IN_ARRAY] ?? false;
                            if (
                                $fieldIsNonNullArrayItemsType
                                && is_array($value)
                                && array_filter(
                                    $value,
                                    fn ($arrayItem) => $arrayItem === null
                                )
                            ) {
                                return $this->errorProvider->getArrayMustNotHaveNullItemsFieldError($fieldName, $value);
                            }
                            $fieldIsArrayOfArraysType = $fieldSchemaDefinition[SchemaDefinition::ARGNAME_IS_ARRAY_OF_ARRAYS] ?? false;
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
                                    fn ($arrayItem) => !is_array($arrayItem) && $arrayItem !== null
                                )
                            ) {
                                return $this->errorProvider->getMustBeArrayOfArraysFieldError($fieldName, $value);
                            }
                            $fieldIsNonNullArrayOfArraysItemsType = $fieldSchemaDefinition[SchemaDefinition::ARGNAME_IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS] ?? false;
                            if (
                                $fieldIsNonNullArrayOfArraysItemsType
                                && is_array($value)
                                && array_filter(
                                    $value,
                                    fn (?array $arrayItem) => $arrayItem === null ? false : array_filter(
                                        $arrayItem,
                                        fn ($arrayItemItem) => $arrayItemItem === null
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
            return $this->errorProvider->getNoFieldResolverProcessesFieldError($this->getID($resultItem), $fieldName, $fieldArgs);
        }

        // Return an error to indicate that no fieldResolver processes this field, which is different than returning a null value.
        // Needed for compatibility with CustomPostUnionTypeResolver (so that data-fields aimed for another post_type are not retrieved)
        $fieldName = $this->fieldQueryInterpreter->getFieldName($field);
        return $this->errorProvider->getNoFieldError($this->getID($resultItem), $fieldName, $this->getMaybeNamespacedTypeName());
    }

    protected function addSchemaDefinition(array $stackMessages, array &$generalMessages, array $options = [])
    {
        parent::addSchemaDefinition($stackMessages, $generalMessages, $options);

        $typeSchemaKey = $this->schemaDefinitionService->getTypeSchemaKey($this);
        $typeName = $this->getMaybeNamespacedTypeName();
        
        // Add the directives (non-global)
        $this->schemaDefinition[$typeSchemaKey][SchemaDefinition::ARGNAME_DIRECTIVES] = [];
        $schemaDirectiveResolvers = $this->getSchemaDirectiveResolvers(false);
        foreach ($schemaDirectiveResolvers as $directiveName => $directiveResolver) {
            $this->schemaDefinition[$typeSchemaKey][SchemaDefinition::ARGNAME_DIRECTIVES][$directiveName] = $this->getDirectiveSchemaDefinition($directiveResolver, $options);
        }

        // Add the fields (non-global)
        $this->schemaDefinition[$typeSchemaKey][SchemaDefinition::ARGNAME_FIELDS] = [];
        $schemaFieldResolvers = $this->getSchemaFieldResolvers(false);
        foreach ($schemaFieldResolvers as $fieldName => $fieldResolver) {
            $this->addFieldSchemaDefinition($fieldResolver, $fieldName, $stackMessages, $generalMessages, $options);
        }

        // Add all the implemented interfaces
        $typeInterfaceDefinitions = [];
        foreach ($this->getAllImplementedInterfaceTypeResolvers() as $interfaceTypeResolver) {
            $interfaceSchemaKey = $this->schemaDefinitionService->getTypeSchemaKey($interfaceTypeResolver);

            // Conveniently get the fields from the schema, which have already been calculated above
            // since they also include their interface fields
            $interfaceFieldNames = $interfaceTypeResolver->getFieldNamesToImplement();
            // The Interface fields may be implemented as either FieldResolver fields or FieldResolver connections,
            // Eg: Interface "Elemental" has field "id" and connection "self"
            // Merge both cases into interface fields
            $interfaceFields = array_filter(
                $this->schemaDefinition[$typeSchemaKey][SchemaDefinition::ARGNAME_FIELDS],
                function ($fieldName) use ($interfaceFieldNames) {
                    return in_array($fieldName, $interfaceFieldNames);
                },
                ARRAY_FILTER_USE_KEY
            );
            $interfaceConnections = array_filter(
                $this->schemaDefinition[$typeSchemaKey][SchemaDefinition::ARGNAME_CONNECTIONS],
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
                SchemaDefinition::ARGNAME_NAME => $interfaceName,
                SchemaDefinition::ARGNAME_NAMESPACED_NAME => $interfaceTypeResolver->getNamespacedTypeName(),
                SchemaDefinition::ARGNAME_ELEMENT_NAME => $interfaceTypeResolver->getTypeName(),
                SchemaDefinition::ARGNAME_DESCRIPTION => $interfaceTypeResolver->getSchemaTypeDescription(),
                SchemaDefinition::ARGNAME_FIELDS => $interfaceFields,
                SchemaDefinition::ARGNAME_INTERFACES => $interfaceImplementedInterfaceNames,
                // The list of types that implement this interface
                SchemaDefinition::ARGNAME_POSSIBLE_TYPES => &$interfacePossibleTypes,
            ];
        }
        $this->schemaDefinition[$typeSchemaKey][SchemaDefinition::ARGNAME_INTERFACES] = $typeInterfaceDefinitions;
    }

    protected function addFieldSchemaDefinition(FieldResolverInterface $fieldResolver, string $fieldName, array $stackMessages, array &$generalMessages, array $options = [])
    {
        /**
         * Fields may not be directly visible in the schema
         */
        if ($fieldResolver->skipAddingToSchemaDefinition($this, $fieldName)) {
            return;
        }

        // Watch out! We are passing empty $fieldArgs to generate the schema!
        $fieldSchemaDefinition = $fieldResolver->getSchemaDefinitionForField($this, $fieldName, []);
        // Add subfield schema if it is deep, and this typeResolver has not been processed yet
        if ($options['deep'] ?? null) {
            // If this field is relational, then add its own schema
            $fieldTypeResolverClass = $this->getFieldTypeResolverClass($fieldName);
            if (SchemaHelpers::isRelationalFieldTypeResolverClass($fieldTypeResolverClass)) {
                $fieldTypeResolver = $this->instanceManager->getInstance($fieldTypeResolverClass);
                $fieldSchemaDefinition[SchemaDefinition::ARGNAME_TYPE_SCHEMA] = $fieldTypeResolver->getSchemaDefinition($stackMessages, $generalMessages, $options);
            }
        }
        // Convert the field type from its internal representation (eg: "array:id") to the type (eg: "array:Post")
        if (!($options['useTypeName'] ?? null) && ($types = $fieldSchemaDefinition[SchemaDefinition::ARGNAME_TYPE_SCHEMA] ?? null)) {
            // Display the type under entry "referencedType"
            $typeNames = array_keys($types);
            $fieldSchemaDefinition[SchemaDefinition::ARGNAME_REFERENCED_TYPE] = $typeNames[0];
        }
        $isGlobal = $fieldResolver->isGlobal($this, $fieldName);
        $isConnection = isset($fieldSchemaDefinition[SchemaDefinition::ARGNAME_RELATIONAL]) && $fieldSchemaDefinition[SchemaDefinition::ARGNAME_RELATIONAL];
        if ($isGlobal) {
            // If it is relational, it is a global connection
            if ($isConnection) {
                $entry = SchemaDefinition::ARGNAME_GLOBAL_CONNECTIONS;
                // Remove the "types"
                if ($options['useTypeName'] ?? null) {
                    unset($fieldSchemaDefinition[SchemaDefinition::ARGNAME_TYPE_SCHEMA]);
                }
            } else {
                $entry = SchemaDefinition::ARGNAME_GLOBAL_FIELDS;
            }
        } else {
            // Split the results into "fields" and "connections"
            $entry = $isConnection ?
                SchemaDefinition::ARGNAME_CONNECTIONS :
                SchemaDefinition::ARGNAME_FIELDS;
        }
        // Can remove attribute "relational"
        if ($isConnection) {
            unset($fieldSchemaDefinition[SchemaDefinition::ARGNAME_RELATIONAL]);
        }
        $typeSchemaKey = $this->schemaDefinitionService->getTypeSchemaKey($this);
        $this->schemaDefinition[$typeSchemaKey][$entry][$fieldName] = $fieldSchemaDefinition;
    }
}
