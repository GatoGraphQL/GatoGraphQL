<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ErrorHandling;

use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\ErrorHandling\ErrorCodes;
use PoP\ComponentModel\ErrorHandling\ErrorDataTokens;
use PoP\Translation\TranslationAPIInterface;

class ErrorProvider implements ErrorProviderInterface
{
    public function __construct(protected TranslationAPIInterface $translationAPI)
    {        
    }

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
    ): Error {
        return new Error(
            $errorCode,
            $errorMessage,
            array_merge(
                [ErrorDataTokens::FIELD_NAME => $fieldName],
                $data ?? []
            ),
            $nestedErrors
        );
    }

    /**
     * Return an error to indicate that no fieldResolver processes this field,
     * which is different than returning a null value.
     * Needed for compatibility with CustomPostUnionTypeResolver,
     * so that data-fields aimed for another post_type are not retrieved
     */
    public function getNoFieldError(string | int $resultItemID, string $fieldName, string $typeName): Error
    {
        return $this->getError(
            $fieldName,
            ErrorCodes::NO_FIELD,
            sprintf(
                $this->translationAPI->__('There is no resolver for field \'%s\' on type \'%s\' and ID \'%s\'', 'pop-component-model'),
                $fieldName,
                $typeName,
                $resultItemID
            )
        );
    }

    /**
     * Return an error to indicate that a non-nullable field is returning a `null` value
     */
    public function getNonNullableFieldError(string $fieldName): Error
    {
        return $this->getError(
            $fieldName,
            ErrorCodes::NON_NULLABLE_FIELD,
            sprintf(
                $this->translationAPI->__('Non-nullable field \'%s\' cannot return null', 'pop-component-model'),
                $fieldName
            )
        );
    }

    /**
     * Return an error to indicate that an array field is returning a non-array value
     */
    public function getMustBeArrayFieldError(string $fieldName, mixed $value): Error
    {
        return $this->getError(
            $fieldName,
            ErrorCodes::MUST_BE_ARRAY_FIELD,
            sprintf(
                $this->translationAPI->__('Field \'%s\' must return an array, but returned \'%s\'', 'pop-component-model'),
                $fieldName,
                (string) $value
            )
        );
    }

    /**
     * Return an error to indicate that a non-array field is returning an array value
     */
    public function getMustNotBeArrayFieldError(string $fieldName, array $value): Error
    {
        return $this->getError(
            $fieldName,
            ErrorCodes::MUST_NOT_BE_ARRAY_FIELD,
            sprintf(
                $this->translationAPI->__('Field \'%s\' must not return an array, but returned \'%s\'', 'pop-component-model'),
                $fieldName,
                json_encode($value)
            )
        );
    }

    /**
     * Return an error to indicate that a non-empty-array field is returning an empty array value
     */
    public function getMustNotBeEmptyArrayFieldError(string $fieldName, array $value): Error
    {
        return $this->getError(
            $fieldName,
            ErrorCodes::MUST_NOT_BE_EMPTY_ARRAY_FIELD,
            sprintf(
                $this->translationAPI->__('Field \'%s\' must not return an empty array', 'pop-component-model'),
                $fieldName
            )
        );
    }

    /**
     * Return an error to indicate that no fieldResolver processes this field,
     * which is different than returning a null value.
     * Needed for compatibility with CustomPostUnionTypeResolver
     * (so that data-fields aimed for another post_type are not retrieved)
     */
    public function getValidationFailedError(string $fieldName, array $fieldArgs, array $validationDescriptions): Error
    {
        if (count($validationDescriptions) == 1) {
            return $this->getError(
                $fieldName,
                ErrorCodes::VALIDATION_FAILED,
                $validationDescriptions[0]
            );
        }
        return $this->getError(
            $fieldName,
            ErrorCodes::VALIDATION_FAILED,
            sprintf(
                $this->translationAPI->__('Field \'%s\' could not be processed due to previous error(s): \'%s\'', 'pop-component-model'),
                $fieldName,
                implode($this->translationAPI->__('\', \'', 'pop-component-model'), $validationDescriptions)
            )
        );
    }

    public function getNoFieldResolverProcessesFieldError(string | int $resultItemID, string $fieldName, array $fieldArgs): Error
    {
        return $this->getError(
            $fieldName,
            ErrorCodes::NO_FIELD_RESOLVER_UNIT_PROCESSES_FIELD,
            sprintf(
                $this->translationAPI->__('No FieldResolver processes field \'%s\' for object with ID \'%s\'', 'pop-component-model'),
                $fieldName,
                (string) $resultItemID
            )
        );
    }
    
    protected function getNestedArgumentError(
        string $fieldName,
        string $errorCode,
        array $argumentErrors
    ): Error {
        return $this->getError(
            $fieldName,
            $errorCode,
            sprintf(
                $this->translationAPI->__('Field \'%s\' could not be processed due to the error(s) from its arguments', 'pop-component-model'),
                $fieldName
            ),
            [
                'argumentErrors' => $argumentErrors,
            ]
        );
    }

    public function getNestedSchemaErrorsFieldError(array $schemaErrors, string $fieldName): Error
    {
        return $this->getNestedArgumentError(
            $fieldName,
            ErrorCodes::NESTED_SCHEMA_ERRORS,
            $schemaErrors
        );
    }

    public function getNestedDBErrorsFieldError(array $dbErrors, string $fieldName): Error
    {
        return $this->getNestedArgumentError(
            $fieldName,
            ErrorCodes::NESTED_DB_ERRORS,
            $dbErrors
        );
    }

    /**
     * @param Error[] $nestedErrors
     */
    public function getNestedErrorsFieldError(array $nestedErrors, string $fieldName): Error
    {
        return $this->getError(
            $fieldName,
            ErrorCodes::NESTED_ERRORS,
            sprintf(
                $this->translationAPI->__('Field \'%s\' could not be processed due to the error(s) from its arguments', 'pop-component-model'),
                $fieldName
            ),
            null,
            $nestedErrors
        );
    }
}
