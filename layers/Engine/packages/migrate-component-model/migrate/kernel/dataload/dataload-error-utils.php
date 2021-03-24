<?php
namespace PoP\ComponentModel;

use PoP\ComponentModel\ErrorHandling\Error;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\ErrorHandling\ErrorCodes;
use PoP\ComponentModel\ErrorHandling\ErrorDataTokens;

class ErrorUtils
{
    // public static function getNoDirectiveError(string $directiveName): Error
    // {
    //     $translationAPI = TranslationAPIFacade::getInstance();
    //     return self::getError(
    //         $directiveName,
    //         ErrorCodes::NO_DIRECTIVE,
    //         $translationAPI->__('No DirectiveResolver resolves this directive', 'pop-component-model')
    //     );
    // }

    /**
     * Return an error to indicate that no fieldResolver processes this field,
     * which is different than returning a null value.
     * Needed for compatibility with CustomPostUnionTypeResolver,
     * so that data-fields aimed for another post_type are not retrieved
     *
     * @param string $fieldName
     * @return Error
     */
    public static function getNoFieldError(string $fieldName): Error
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return self::getError(
            $fieldName,
            ErrorCodes::NO_FIELD,
            sprintf(
                $translationAPI->__('No FieldResolver resolves field \'%s\'', 'pop-component-model'),
                $fieldName
            )
        );
    }

    /**
     * Return an error to indicate that a non-nullable field is returning a `null` value
     *
     * @param string $fieldName
     * @return Error
     */
    public static function getNonNullableFieldError(string $fieldName): Error
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return self::getError(
            $fieldName,
            ErrorCodes::NON_NULLABLE_FIELD,
            sprintf(
                $translationAPI->__('Non-nullable field \'%s\' cannot return null', 'pop-component-model'),
                $fieldName
            )
        );
    }
    public static function getValidationFailedError(string $fieldName, array $fieldArgs, array $validationDescriptions): Error
    {
        // Return an error to indicate that no fieldResolver processes this field, which is different than returning a null value.
        // Needed for compatibility with CustomPostUnionTypeResolver (so that data-fields aimed for another post_type are not retrieved)
        if (count($validationDescriptions) == 1) {
            return self::getError(
                $fieldName,
                ErrorCodes::VALIDATION_FAILED,
                $validationDescriptions[0]
            );
        }
        $translationAPI = TranslationAPIFacade::getInstance();
        $error = self::getError(
            $fieldName,
            ErrorCodes::VALIDATION_FAILED,
            sprintf(
                $translationAPI->__('Field \'%s\' could not be processed due to previous error(s)', 'pop-component-model'),
                $fieldName
            )
        );
        foreach ($validationDescriptions as $validationDescription) {
            $error->add(
                'nested-error',
                $validationDescription
            );
        }
        return $error;
    }
    public static function getNoFieldResolverProcessesFieldError(string | int $resultItemID, string $fieldName, array $fieldArgs): Error
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return self::getError(
            $fieldName,
            ErrorCodes::NO_FIELD_RESOLVER_UNIT_PROCESSES_FIELD,
            sprintf(
                $translationAPI->__('No FieldResolver processes field \'%s\' for object with ID \'%s\'', 'pop-component-model'),
                $fieldName,
                (string) $resultItemID
            )
        );
    }
    public static function getNestedSchemaErrorsFieldError(array $schemaErrors, string $fieldName): Error
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $error = self::getError(
            $fieldName,
            ErrorCodes::NESTED_SCHEMA_ERRORS,
            sprintf(
                $translationAPI->__('Field \'%s\' could not be processed due to the error(s) from its arguments', 'pop-component-model'),
                $fieldName
            )
        );
        foreach ($schemaErrors as $schemaError) {
            $error->add(
                'nested-error',
                $schemaError
            );
        }

        return $error;
    }
    public static function getNestedDBErrorsFieldError(array $dbErrors, string $fieldName): Error
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $error = self::getError(
            $fieldName,
            ErrorCodes::NESTED_DB_ERRORS,
            sprintf(
                $translationAPI->__('Field \'%s\' could not be processed due to the error(s) from its arguments', 'pop-component-model'),
                $fieldName
            )
        );
        foreach ($dbErrors as $resultItemID => $fieldOutputKeyErrors) {
            foreach ($fieldOutputKeyErrors as $fieldOutputKey => $errors) {
                foreach ($errors as $dbError) {
                    $error->add(
                        'nested-error',
                        sprintf(
                            $translationAPI->__('Field \'%s\' could not be processed due to error: %s', 'pop-component-model'),
                            $fieldOutputKey,
                            $dbError
                        )
                    );
                }
            }
        }

        return $error;
    }
    public static function getNestedErrorsFieldError(array $errors, string $fieldName): Error
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $error = self::getError(
            $fieldName,
            ErrorCodes::NESTED_ERRORS,
            sprintf(
                $translationAPI->__('Field \'%s\' could not be processed due to the error(s) from its arguments', 'pop-component-model'),
                $fieldName
            )
        );
        foreach ($errors as $nestedError) {
            foreach ($nestedError->getErrorMessages() as $nestedErrorMessage) {
                $error->add(
                    'nested-error',
                    $nestedErrorMessage
                );
            }
        }

        return $error;
    }
    public static function getError(string $fieldName, string $errorCode, string $errorMessage): Error
    {
        return new Error(
            $errorCode,
            $errorMessage,
            [ErrorDataTokens::FIELD_NAME => $fieldName]
        );
    }
}
