<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Error;

interface ErrorProviderInterface
{
    /**
     * @param array<string, mixed>|null $data
     * @param Error[]|null $nestedErrors
     */
    public function getError(
        string $fieldName,
        string $errorCode,
        string $errorMessage,
        ?array $data = null,
        ?array $nestedErrors = null
    ): Error;
    /**
     * Return an error to indicate that no fieldResolver processes this field,
     * which is different than returning a null value.
     * Needed for compatibility with CustomPostUnionTypeResolver,
     * so that data-fields aimed for another post_type are not retrieved
     */
    public function getNoFieldError(string | int $objectID, string $fieldName, string $typeName): Error;

    /**
     * Return an error to indicate that a non-nullable field is returning a `null` value
     */
    public function getNonNullableFieldError(string $fieldName): Error;
    public function getMustNotBeArrayFieldError(string $fieldName, array $value): Error;
    public function getMustBeArrayFieldError(string $fieldName, mixed $value): Error;
    public function getArrayMustNotHaveNullItemsFieldError(string $fieldName, array $value): Error;
    public function getMustBeArrayOfArraysFieldError(string $fieldName, mixed $value): Error;
    public function getMustNotBeArrayOfArraysFieldError(string $fieldName, mixed $value): Error;
    public function getArrayOfArraysMustNotHaveNullItemsFieldError(string $fieldName, array $value): Error;
    /**
     * Return an error to indicate that no fieldResolver processes this field,
     * which is different than returning a null value.
     * Needed for compatibility with CustomPostUnionTypeResolver
     * (so that data-fields aimed for another post_type are not retrieved)
     */
    public function getValidationFailedError(string $fieldName, array $fieldArgs, array $validationDescriptions): Error;
    public function getNoObjectTypeFieldResolverProcessesFieldError(
        string $objectTypeName,
        string | int $objectID,
        string $fieldName,
        array $fieldArgs,
    ): Error;
    /**
     * @param string[] $schemaErrors
     */
    public function getNestedSchemaErrorsFieldError(array $schemaErrors, string $fieldName): Error;
    /**
     * @param string[] $objectErrors
     */
    public function getNestedObjectErrorsFieldError(array $objectErrors, string $fieldName): Error;
    /**
     * @param Error[] $nestedErrors
     */
    public function getNestedErrorsFieldError(array $nestedErrors, string $fieldName): Error;
}
