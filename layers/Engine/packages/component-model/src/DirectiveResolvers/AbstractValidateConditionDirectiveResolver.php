<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\Feedback\Tokens;
use PoP\ComponentModel\TypeResolvers\PipelinePositions;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

abstract class AbstractValidateConditionDirectiveResolver extends AbstractValidateDirectiveResolver
{
    // /**
    //  * Validations are naturally added through code and not through the query, so no need to expose them in the schema
    //  */
    // public function skipExposingDirectiveInSchema(RelationalTypeResolverInterface $relationalTypeResolver): bool {
    //     return true;
    // }

    /**
     * If validating a directive, place it after resolveAndMerge
     * Otherwise, before
     */
    public function getPipelinePosition(): string
    {
        if ($this->isValidatingDirective()) {
            return PipelinePositions::AFTER_RESOLVE;
        }
        return PipelinePositions::AFTER_VALIDATE_BEFORE_RESOLVE;
    }

    /**
     * Validate a custom condition
     */
    protected function validateFields(RelationalTypeResolverInterface $relationalTypeResolver, array $dataFields, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations, array &$variables, array &$failedDataFields): void
    {
        if (!$this->validateCondition($relationalTypeResolver)) {
            // All fields failed
            $failedDataFields = array_merge(
                $failedDataFields,
                $dataFields
            );
            $schemaErrors[] = [
                Tokens::PATH => $dataFields,
                Tokens::MESSAGE => $this->getValidationFailedMessage($relationalTypeResolver, $dataFields),
            ];
        }
    }

    /**
     * Condition to validate. Return `true` for success, `false` for failure
     */
    abstract protected function validateCondition(RelationalTypeResolverInterface $relationalTypeResolver): bool;

    /**
     * Show a different error message depending on if we are validating the whole field, or a directive
     * By default, validate the whole field
     */
    protected function isValidatingDirective(): bool
    {
        return false;
    }

    protected function getValidationFailedMessage(RelationalTypeResolverInterface $relationalTypeResolver, array $failedDataFields): string
    {
        $errorMessage = $this->isValidatingDirective() ?
            $this->__('Validation failed for directives in fields \'%s\'', 'component-model') :
            $this->__('Validation failed for fields \'%s\'', 'component-model');
        return sprintf(
            $errorMessage,
            implode(
                $this->__('\', \''),
                $failedDataFields
            )
        );
    }
}
