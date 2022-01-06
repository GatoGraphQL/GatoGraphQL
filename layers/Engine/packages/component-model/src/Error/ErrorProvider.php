<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Error;

use PoP\BasicService\BasicServiceTrait;
use PoP\ComponentModel\ObjectSerialization\ObjectSerializationManagerInterface;
use PoP\GraphQLParser\Response\OutputServiceInterface;
use stdClass;

class ErrorProvider implements ErrorProviderInterface
{
    use BasicServiceTrait;

    private ?ObjectSerializationManagerInterface $objectSerializationManager = null;
    private ?OutputServiceInterface $outputService = null;

    final public function setObjectSerializationManager(ObjectSerializationManagerInterface $objectSerializationManager): void
    {
        $this->objectSerializationManager = $objectSerializationManager;
    }
    final protected function getObjectSerializationManager(): ObjectSerializationManagerInterface
    {
        return $this->objectSerializationManager ??= $this->instanceManager->getInstance(ObjectSerializationManagerInterface::class);
    }
    final public function setOutputService(OutputServiceInterface $outputService): void
    {
        $this->outputService = $outputService;
    }
    final protected function getOutputService(): OutputServiceInterface
    {
        return $this->outputService ??= $this->instanceManager->getInstance(OutputServiceInterface::class);
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
    public function getNoFieldError(string | int $objectID, string $fieldName, string $typeName): Error
    {
        return $this->getError(
            $fieldName,
            ErrorCodes::NO_FIELD,
            sprintf(
                $this->__('There is no field \'%s\' on type \'%s\' and ID \'%s\'', 'pop-component-model'),
                $fieldName,
                $typeName,
                $objectID
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
                $this->__('Non-nullable field \'%s\' cannot return null', 'pop-component-model'),
                $fieldName
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
                $this->__('Field \'%s\' must not return an array, but returned \'%s\'', 'pop-component-model'),
                $fieldName,
                $this->getOutputService()->jsonEncodeArrayOrStdClassValue($value)
            )
        );
    }

    /**
     * Return an error to indicate that an array field is returning a non-array value
     */
    public function getMustBeArrayFieldError(string $fieldName, mixed $value): Error
    {
        if ($value instanceof stdClass) {
            $valueAsString = $this->getOutputService()->jsonEncodeArrayOrStdClassValue($value);
        } elseif (is_object($value)) {
            $valueAsString = $this->getObjectSerializationManager()->serialize($value);
        } else {
            $valueAsString = (string) $value;
        }
        return $this->getError(
            $fieldName,
            ErrorCodes::MUST_BE_ARRAY_FIELD,
            sprintf(
                $this->__('Field \'%s\' must return an array, but returned \'%s\'', 'pop-component-model'),
                $fieldName,
                $valueAsString
            )
        );
    }

    /**
     * Return an error to indicate that an array field is returning an array with null items
     */
    public function getArrayMustNotHaveNullItemsFieldError(string $fieldName, array $value): Error
    {
        return $this->getError(
            $fieldName,
            ErrorCodes::ARRAY_MUST_NOT_HAVE_EMPTY_ITEMS_FIELD,
            sprintf(
                $this->__('Field \'%s\' must not return an array with null items', 'pop-component-model'),
                $fieldName
            )
        );
    }

    public function getMustNotBeArrayOfArraysFieldError(string $fieldName, mixed $value): Error
    {
        return $this->getError(
            $fieldName,
            ErrorCodes::MUST_BE_ARRAY_OF_ARRAYS_FIELD,
            sprintf(
                $this->__('Array value in field \'%s\' must not contain arrays, but returned \'%s\'', 'pop-component-model'),
                $fieldName,
                $this->getOutputService()->jsonEncodeArrayOrStdClassValue($value)
            )
        );
    }

    public function getMustBeArrayOfArraysFieldError(string $fieldName, mixed $value): Error
    {
        return $this->getError(
            $fieldName,
            ErrorCodes::MUST_BE_ARRAY_OF_ARRAYS_FIELD,
            sprintf(
                $this->__('Field \'%s\' must return an array of arrays, but returned \'%s\'', 'pop-component-model'),
                $fieldName,
                $this->getOutputService()->jsonEncodeArrayOrStdClassValue($value)
            )
        );
    }

    public function getArrayOfArraysMustNotHaveNullItemsFieldError(string $fieldName, array $value): Error
    {
        return $this->getError(
            $fieldName,
            ErrorCodes::ARRAY_OF_ARRAYS_MUST_NOT_HAVE_EMPTY_ITEMS_FIELD,
            sprintf(
                $this->__('Field \'%s\' must not return an array of arrays with null items', 'pop-component-model'),
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
                $this->__('Field \'%s\' could not be processed due to previous error(s): \'%s\'', 'pop-component-model'),
                $fieldName,
                implode($this->__('\', \'', 'pop-component-model'), $validationDescriptions)
            )
        );
    }

    public function getNoObjectTypeFieldResolverProcessesFieldError(
        string $objectTypeName,
        string | int $objectID,
        string $fieldName,
        array $fieldArgs,
    ): Error {
        return $this->getError(
            $fieldName,
            ErrorCodes::NO_FIELD_RESOLVER_UNIT_PROCESSES_FIELD,
            sprintf(
                $this->__('No FieldResolver for object type \'%s\' processes field \'%s\' for object with ID \'%s\'', 'pop-component-model'),
                $objectTypeName,
                $fieldName,
                (string) $objectID
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
                $this->__('Field \'%s\' could not be processed due to the error(s) from its arguments', 'pop-component-model'),
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

    public function getNestedObjectErrorsFieldError(array $objectErrors, string $fieldName): Error
    {
        return $this->getNestedArgumentError(
            $fieldName,
            ErrorCodes::NESTED_DB_ERRORS,
            $objectErrors
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
                $this->__('Field \'%s\' could not be processed due to the error(s) from its arguments', 'pop-component-model'),
                $fieldName
            ),
            null,
            $nestedErrors
        );
    }
}
