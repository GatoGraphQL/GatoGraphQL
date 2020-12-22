<?php
namespace PoP\ComponentModel;

use PoP\ComponentModel\ErrorHandling\Error;
use PoP\Translation\Facades\TranslationAPIFacade;

class ErrorUtils
{
    // public const ERRORCODE_NODIRECTIVE = 'no-directive';
    public const ERRORCODE_NONNULLABLEFIELD = 'non-nullable-field';
    public const ERRORCODE_NOFIELD = 'no-field';
    public const ERRORCODE_VALIDATIONFAILED = 'validation-failed';
    public const ERRORCODE_NOFIELDRESOLVERUNITPROCESSESFIELD = 'no-fieldresolverunit-processes-field';
    public const ERRORCODE_NESTEDSCHEMAERRORS = 'nested-schema-errors';
    public const ERRORCODE_NESTEDDBERRORS = 'nested-db-errors';
    public const ERRORCODE_NESTEDERRORS = 'nested-errors';

    public const ERRORDATA_FIELD_NAME = 'fieldName';

    // public static function getNoDirectiveError(string $directiveName): Error
    // {
    //     $translationAPI = TranslationAPIFacade::getInstance();
    //     return self::getError(
    //         $directiveName,
    //         self::ERRORCODE_NODIRECTIVE,
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
            self::ERRORCODE_NOFIELD,
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
            self::ERRORCODE_NONNULLABLEFIELD,
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
                self::ERRORCODE_VALIDATIONFAILED,
                $validationDescriptions[0]
            );
        }
        $translationAPI = TranslationAPIFacade::getInstance();
        $error = self::getError(
            $fieldName,
            self::ERRORCODE_VALIDATIONFAILED,
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
    public static function getNoFieldResolverProcessesFieldError($resultItemID, string $fieldName, array $fieldArgs): Error
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return self::getError(
            $fieldName,
            self::ERRORCODE_NOFIELDRESOLVERUNITPROCESSESFIELD,
            sprintf(
                $translationAPI->__('No FieldResolver processes field \'%s\' for object with ID \'%s\'', 'pop-component-model'),
                $fieldName,
                $resultItemID
            )
        );
    }
    public static function getNestedSchemaErrorsFieldError(array $schemaErrors, string $fieldName): Error
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $error = self::getError(
            $fieldName,
            self::ERRORCODE_NESTEDSCHEMAERRORS,
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
            self::ERRORCODE_NESTEDDBERRORS,
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
            self::ERRORCODE_NESTEDERRORS,
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
            [self::ERRORDATA_FIELD_NAME => $fieldName]
        );
    }
}
