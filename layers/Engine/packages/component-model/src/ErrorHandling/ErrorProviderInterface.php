<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ErrorHandling;

use PoP\ComponentModel\ErrorHandling\Error;

interface ErrorProviderInterface
{
    public function getError(string $fieldName, string $errorCode, string $errorMessage): Error;
    // public function getNoDirectiveError(string $directiveName): Error;
    /**
     * Return an error to indicate that no fieldResolver processes this field,
     * which is different than returning a null value.
     * Needed for compatibility with CustomPostUnionTypeResolver,
     * so that data-fields aimed for another post_type are not retrieved
     */
    public function getNoFieldError(string | int $resultItemID, string $fieldName, string $typeName): Error;

    /**
     * Return an error to indicate that a non-nullable field is returning a `null` value
     *
     * @param string $fieldName
     * @return Error
     */
    public function getNonNullableFieldError(string $fieldName): Error;
    public function getMustBeArrayFieldError(string $fieldName, mixed $value): Error;
    public function getMustNotBeArrayFieldError(string $fieldName, array $value): Error;
    public function getValidationFailedError(string $fieldName, array $fieldArgs, array $validationDescriptions): Error;
    public function getNoFieldResolverProcessesFieldError(string | int $resultItemID, string $fieldName, array $fieldArgs): Error;
    public function getNestedSchemaErrorsFieldError(array $schemaErrors, string $fieldName): Error;
    public function getNestedDBErrorsFieldError(array $dbErrors, string $fieldName): Error;
    public function getNestedErrorsFieldError(array $errors, string $fieldName): Error;
}
