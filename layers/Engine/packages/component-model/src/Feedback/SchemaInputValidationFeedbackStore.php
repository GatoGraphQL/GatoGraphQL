<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

class SchemaInputValidationFeedbackStore
{
    /** @var SchemaInputValidationFeedbackInterface[] */
    private array $errors = [];
    /** @var SchemaInputValidationFeedbackInterface[] */
    private array $warnings = [];
    /** @var SchemaInputValidationFeedbackInterface[] */
    private array $deprecations = [];
    /** @var SchemaInputValidationFeedbackInterface[] */
    private array $notices = [];
    /** @var SchemaInputValidationFeedbackInterface[] */
    private array $suggestions = [];
    /** @var SchemaInputValidationFeedbackInterface[] */
    private array $logs = [];

    public function incorporate(
        SchemaInputValidationFeedbackStore $schemaInputValidationFeedbackStore,
    ): void {
        $this->errors = array_merge(
            $this->errors,
            $schemaInputValidationFeedbackStore->getErrors()
        );
        $this->warnings = array_merge(
            $this->warnings,
            $schemaInputValidationFeedbackStore->getWarnings()
        );
        $this->deprecations = array_merge(
            $this->deprecations,
            $schemaInputValidationFeedbackStore->getDeprecations()
        );
        $this->notices = array_merge(
            $this->notices,
            $schemaInputValidationFeedbackStore->getNotices()
        );
        $this->suggestions = array_merge(
            $this->suggestions,
            $schemaInputValidationFeedbackStore->getSuggestions()
        );
        $this->logs = array_merge(
            $this->logs,
            $schemaInputValidationFeedbackStore->getLogs()
        );
    }

    /**
     * @return SchemaInputValidationFeedbackInterface[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function addError(SchemaInputValidationFeedbackInterface $error): void
    {
        $this->errors[] = $error;
    }

    /**
     * @param SchemaInputValidationFeedbackInterface[] $errors
     */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }

    /**
     * @return SchemaInputValidationFeedbackInterface[]
     */
    public function getWarnings(): array
    {
        return $this->warnings;
    }

    public function addWarning(SchemaInputValidationFeedbackInterface $warning): void
    {
        $this->warnings[] = $warning;
    }

    /**
     * @param SchemaInputValidationFeedbackInterface[] $warnings
     */
    public function setWarnings(array $warnings): void
    {
        $this->warnings = $warnings;
    }

    /**
     * @return SchemaInputValidationFeedbackInterface[]
     */
    public function getDeprecations(): array
    {
        return $this->deprecations;
    }

    public function addDeprecation(SchemaInputValidationFeedbackInterface $deprecation): void
    {
        $this->deprecations[] = $deprecation;
    }

    /**
     * @param SchemaInputValidationFeedbackInterface[] $deprecations
     */
    public function setDeprecations(array $deprecations): void
    {
        $this->deprecations = $deprecations;
    }

    /**
     * @return SchemaInputValidationFeedbackInterface[]
     */
    public function getNotices(): array
    {
        return $this->notices;
    }

    public function addNotice(SchemaInputValidationFeedbackInterface $notice): void
    {
        $this->notices[] = $notice;
    }

    /**
     * @param SchemaInputValidationFeedbackInterface[] $notices
     */
    public function setNotices(array $notices): void
    {
        $this->notices = $notices;
    }

    /**
     * @return SchemaInputValidationFeedbackInterface[]
     */
    public function getSuggestions(): array
    {
        return $this->suggestions;
    }

    public function addSuggestion(SchemaInputValidationFeedbackInterface $suggestion): void
    {
        $this->suggestions[] = $suggestion;
    }

    /**
     * @param SchemaInputValidationFeedbackInterface[] $suggestions
     */
    public function setSuggestions(array $suggestions): void
    {
        $this->suggestions = $suggestions;
    }

    /**
     * @return SchemaInputValidationFeedbackInterface[]
     */
    public function getLogs(): array
    {
        return $this->logs;
    }

    public function addLog(SchemaInputValidationFeedbackInterface $log): void
    {
        $this->logs[] = $log;
    }

    /**
     * @param SchemaInputValidationFeedbackInterface[] $logs
     */
    public function setLogs(array $logs): void
    {
        $this->logs = $logs;
    }
}
