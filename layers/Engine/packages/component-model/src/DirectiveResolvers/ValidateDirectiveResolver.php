<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\Directives\DirectiveTypes;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\PipelinePositions;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\AbstractValidateDirectiveResolver;

class ValidateDirectiveResolver extends AbstractValidateDirectiveResolver
{
    const DIRECTIVE_NAME = 'validate';
    public static function getDirectiveName(): string
    {
        return self::DIRECTIVE_NAME;
    }

    /**
     * This is a system directive
     *
     * @return string
     */
    public function getDirectiveType(): string
    {
        return DirectiveTypes::SYSTEM;
    }

    /**
     * Execute only once
     *
     * @return boolean
     */
    public function isRepeatable(): bool
    {
        return false;
    }

    /**
     * This directive must be the first one of the group at the middle
     *
     * @return void
     */
    public function getPipelinePosition(): string
    {
        return PipelinePositions::AFTER_VALIDATE_BEFORE_RESOLVE;
    }

    protected function validateFields(TypeResolverInterface $typeResolver, array $dataFields, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations, array &$variables, array &$failedDataFields): void
    {
        foreach ($dataFields as $field) {
            $success = $this->validateField($typeResolver, $field, $schemaErrors, $schemaWarnings, $schemaDeprecations, $variables);
            if (!$success) {
                $failedDataFields[] = $field;
            }
        }
    }

    protected function validateField(TypeResolverInterface $typeResolver, string $field, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations, array &$variables): bool
    {
        // Check for errors first, warnings and deprecations then
        $success = true;
        if ($schemaValidationErrors = $typeResolver->resolveSchemaValidationErrorDescriptions($field, $variables)) {
            $schemaErrors = array_merge(
                $schemaErrors,
                $schemaValidationErrors
            );
            $success = false;
        }
        if ($schemaValidationWarnings = $typeResolver->resolveSchemaValidationWarningDescriptions($field, $variables)) {
            $schemaWarnings = array_merge(
                $schemaWarnings,
                $schemaValidationWarnings
            );
        }
        if ($schemaValidationDeprecations = $typeResolver->resolveSchemaDeprecationDescriptions($field, $variables)) {
            $schemaDeprecations = array_merge(
                $schemaDeprecations,
                $schemaValidationDeprecations
            );
        }
        return $success;
    }

    public function getSchemaDirectiveDescription(TypeResolverInterface $typeResolver): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('It validates the field, filtering out those field arguments that raised a warning, or directly invalidating the field if any field argument raised an error. For instance, if a mandatory field argument is not provided, then it is an error and the field is invalidated and removed from the output; if a field argument can\'t be casted to its intended type, then it is a warning, the affected field argument is removed and the field is executed without it. This directive is already included by the engine, since its execution is mandatory', 'component-model');
    }
}
