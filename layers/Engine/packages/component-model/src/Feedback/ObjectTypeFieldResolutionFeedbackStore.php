<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

class ObjectTypeFieldResolutionFeedbackStore
{
    /** @var ObjectTypeFieldResolutionFeedbackInterface[] */
    private array $errors = [];
    /** @var ObjectTypeFieldResolutionFeedbackInterface[] */
    private array $warnings = [];
    /** @var ObjectTypeFieldResolutionFeedbackInterface[] */
    private array $deprecations = [];
    /** @var ObjectTypeFieldResolutionFeedbackInterface[] */
    private array $notices = [];
    /** @var ObjectTypeFieldResolutionFeedbackInterface[] */
    private array $suggestions = [];
    /** @var ObjectTypeFieldResolutionFeedbackInterface[] */
    private array $logs = [];

    public function incorporate(
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $this->errors = array_merge(
            $this->errors,
            $objectTypeFieldResolutionFeedbackStore->getErrors()
        );
        $this->warnings = array_merge(
            $this->warnings,
            $objectTypeFieldResolutionFeedbackStore->getWarnings()
        );
        $this->deprecations = array_merge(
            $this->deprecations,
            $objectTypeFieldResolutionFeedbackStore->getDeprecations()
        );
        $this->notices = array_merge(
            $this->notices,
            $objectTypeFieldResolutionFeedbackStore->getNotices()
        );
        $this->suggestions = array_merge(
            $this->suggestions,
            $objectTypeFieldResolutionFeedbackStore->getSuggestions()
        );
        $this->logs = array_merge(
            $this->logs,
            $objectTypeFieldResolutionFeedbackStore->getLogs()
        );
    }

    public function incorporateSchemaInputValidation(
        SchemaInputValidationFeedbackStore $schemaInputValidationFeedbackStore,
        RelationalTypeResolverInterface $relationalTypeResolver,
    ): void {
        $this->errors = array_merge(
            $this->errors,
            array_map(
                fn ($schemaInputValidationFeedback) => ObjectTypeFieldResolutionFeedback::fromSchemaInputValidationFeedback(
                    $schemaInputValidationFeedback,
                    $relationalTypeResolver,
                ),
                $schemaInputValidationFeedbackStore->getErrors()
            )
        );
        $this->warnings = array_merge(
            $this->warnings,
            array_map(
                fn ($schemaInputValidationFeedback) => ObjectTypeFieldResolutionFeedback::fromSchemaInputValidationFeedback(
                    $schemaInputValidationFeedback,
                    $relationalTypeResolver,
                ),
                $schemaInputValidationFeedbackStore->getWarnings()
            )
        );
        $this->deprecations = array_merge(
            $this->deprecations,
            array_map(
                fn ($schemaInputValidationFeedback) => ObjectTypeFieldResolutionFeedback::fromSchemaInputValidationFeedback(
                    $schemaInputValidationFeedback,
                    $relationalTypeResolver,
                ),
                $schemaInputValidationFeedbackStore->getDeprecations()
            )
        );
        $this->notices = array_merge(
            $this->notices,
            array_map(
                fn ($schemaInputValidationFeedback) => ObjectTypeFieldResolutionFeedback::fromSchemaInputValidationFeedback(
                    $schemaInputValidationFeedback,
                    $relationalTypeResolver,
                ),
                $schemaInputValidationFeedbackStore->getNotices()
            )
        );
        $this->suggestions = array_merge(
            $this->suggestions,
            array_map(
                fn ($schemaInputValidationFeedback) => ObjectTypeFieldResolutionFeedback::fromSchemaInputValidationFeedback(
                    $schemaInputValidationFeedback,
                    $relationalTypeResolver,
                ),
                $schemaInputValidationFeedbackStore->getSuggestions()
            )
        );
        $this->logs = array_merge(
            $this->logs,
            array_map(
                fn ($schemaInputValidationFeedback) => ObjectTypeFieldResolutionFeedback::fromSchemaInputValidationFeedback(
                    $schemaInputValidationFeedback,
                    $relationalTypeResolver,
                ),
                $schemaInputValidationFeedbackStore->getLogs()
            )
        );
    }

    /**
     * @return ObjectTypeFieldResolutionFeedbackInterface[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function addError(ObjectTypeFieldResolutionFeedbackInterface $error): void
    {
        $this->errors[] = $error;
    }

    /**
     * @param ObjectTypeFieldResolutionFeedbackInterface[] $errors
     */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }

    /**
     * @return ObjectTypeFieldResolutionFeedbackInterface[]
     */
    public function getWarnings(): array
    {
        return $this->warnings;
    }

    public function addWarning(ObjectTypeFieldResolutionFeedbackInterface $warning): void
    {
        $this->warnings[] = $warning;
    }

    /**
     * @param ObjectTypeFieldResolutionFeedbackInterface[] $warnings
     */
    public function setWarnings(array $warnings): void
    {
        $this->warnings = $warnings;
    }

    /**
     * @return ObjectTypeFieldResolutionFeedbackInterface[]
     */
    public function getDeprecations(): array
    {
        return $this->deprecations;
    }

    public function addDeprecation(ObjectTypeFieldResolutionFeedbackInterface $deprecation): void
    {
        $this->deprecations[] = $deprecation;
    }

    /**
     * @param ObjectTypeFieldResolutionFeedbackInterface[] $deprecations
     */
    public function setDeprecations(array $deprecations): void
    {
        $this->deprecations = $deprecations;
    }

    /**
     * @return ObjectTypeFieldResolutionFeedbackInterface[]
     */
    public function getNotices(): array
    {
        return $this->notices;
    }

    public function addNotice(ObjectTypeFieldResolutionFeedbackInterface $notice): void
    {
        $this->notices[] = $notice;
    }

    /**
     * @param ObjectTypeFieldResolutionFeedbackInterface[] $notices
     */
    public function setNotices(array $notices): void
    {
        $this->notices = $notices;
    }

    /**
     * @return ObjectTypeFieldResolutionFeedbackInterface[]
     */
    public function getSuggestions(): array
    {
        return $this->suggestions;
    }

    public function addSuggestion(ObjectTypeFieldResolutionFeedbackInterface $suggestion): void
    {
        $this->suggestions[] = $suggestion;
    }

    /**
     * @param ObjectTypeFieldResolutionFeedbackInterface[] $suggestions
     */
    public function setSuggestions(array $suggestions): void
    {
        $this->suggestions = $suggestions;
    }

    /**
     * @return ObjectTypeFieldResolutionFeedbackInterface[]
     */
    public function getLogs(): array
    {
        return $this->logs;
    }

    public function addLog(ObjectTypeFieldResolutionFeedbackInterface $log): void
    {
        $this->logs[] = $log;
    }

    /**
     * @param ObjectTypeFieldResolutionFeedbackInterface[] $logs
     */
    public function setLogs(array $logs): void
    {
        $this->logs = $logs;
    }
}
