<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

class SchemaFeedbackStore
{
    /** @var SchemaFeedbackInterface[] */
    private array $errors = [];
    /** @var SchemaFeedbackInterface[] */
    private array $warnings = [];
    /** @var SchemaFeedbackInterface[] */
    private array $deprecations = [];
    /** @var SchemaFeedbackInterface[] */
    private array $notices = [];
    /** @var SchemaFeedbackInterface[] */
    private array $suggestions = [];
    /** @var SchemaFeedbackInterface[] */
    private array $logs = [];

    public function incorporate(
        SchemaFeedbackStore $schemaFeedbackStore,
    ): void {
        $this->errors = array_merge(
            $this->errors,
            $schemaFeedbackStore->getErrors()
        );
        $this->warnings = array_merge(
            $this->warnings,
            $schemaFeedbackStore->getWarnings()
        );
        $this->deprecations = array_merge(
            $this->deprecations,
            $schemaFeedbackStore->getDeprecations()
        );
        $this->notices = array_merge(
            $this->notices,
            $schemaFeedbackStore->getNotices()
        );
        $this->suggestions = array_merge(
            $this->suggestions,
            $schemaFeedbackStore->getSuggestions()
        );
        $this->logs = array_merge(
            $this->logs,
            $schemaFeedbackStore->getLogs()
        );
    }

    public function incorporateFromObjectTypeFieldResolutionFeedbackStore(
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $field,
        ?string $directive = null,
    ): void {
        foreach ($objectTypeFieldResolutionFeedbackStore->getErrors() as $objectTypeFieldResolutionFeedbackError) {
            $this->errors[] = SchemaFeedback::fromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedbackError,
                $relationalTypeResolver,
                $field,
                $directive,
            );
        }
        foreach ($objectTypeFieldResolutionFeedbackStore->getWarnings() as $objectTypeFieldResolutionFeedbackWarning) {
            $this->warnings[] = SchemaFeedback::fromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedbackWarning,
                $relationalTypeResolver,
                $field,
                $directive,
            );
        }
        foreach ($objectTypeFieldResolutionFeedbackStore->getDeprecations() as $objectTypeFieldResolutionFeedbackDeprecation) {
            $this->deprecations[] = SchemaFeedback::fromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedbackDeprecation,
                $relationalTypeResolver,
                $field,
                $directive,
            );
        }
        foreach ($objectTypeFieldResolutionFeedbackStore->getNotices() as $objectTypeFieldResolutionFeedbackNotice) {
            $this->notices[] = SchemaFeedback::fromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedbackNotice,
                $relationalTypeResolver,
                $field,
                $directive,
            );
        }
        foreach ($objectTypeFieldResolutionFeedbackStore->getSuggestions() as $objectTypeFieldResolutionFeedbackSuggestion) {
            $this->suggestions[] = SchemaFeedback::fromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedbackSuggestion,
                $relationalTypeResolver,
                $field,
                $directive,
            );
        }
        foreach ($objectTypeFieldResolutionFeedbackStore->getLogs() as $objectTypeFieldResolutionFeedbackLog) {
            $this->logs[] = SchemaFeedback::fromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedbackLog,
                $relationalTypeResolver,
                $field,
                $directive,
            );
        }
    }

    /**
     * @return SchemaFeedbackInterface[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function addError(SchemaFeedbackInterface $error): void
    {
        $this->errors[] = $error;
    }

    /**
     * @param SchemaFeedbackInterface[] $errors
     */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }

    /**
     * @return SchemaFeedbackInterface[]
     */
    public function getWarnings(): array
    {
        return $this->warnings;
    }

    public function addWarning(SchemaFeedbackInterface $warning): void
    {
        $this->warnings[] = $warning;
    }

    /**
     * @param SchemaFeedbackInterface[] $warnings
     */
    public function setWarnings(array $warnings): void
    {
        $this->warnings = $warnings;
    }

    /**
     * @return SchemaFeedbackInterface[]
     */
    public function getDeprecations(): array
    {
        return $this->deprecations;
    }

    public function addDeprecation(SchemaFeedbackInterface $deprecation): void
    {
        $this->deprecations[] = $deprecation;
    }

    /**
     * @param SchemaFeedbackInterface[] $deprecations
     */
    public function setDeprecations(array $deprecations): void
    {
        $this->deprecations = $deprecations;
    }

    /**
     * @return SchemaFeedbackInterface[]
     */
    public function getNotices(): array
    {
        return $this->notices;
    }

    public function addNotice(SchemaFeedbackInterface $notice): void
    {
        $this->notices[] = $notice;
    }

    /**
     * @param SchemaFeedbackInterface[] $notices
     */
    public function setNotices(array $notices): void
    {
        $this->notices = $notices;
    }

    /**
     * @return SchemaFeedbackInterface[]
     */
    public function getSuggestions(): array
    {
        return $this->suggestions;
    }

    public function addSuggestion(SchemaFeedbackInterface $suggestion): void
    {
        $this->suggestions[] = $suggestion;
    }

    /**
     * @param SchemaFeedbackInterface[] $suggestions
     */
    public function setSuggestions(array $suggestions): void
    {
        $this->suggestions = $suggestions;
    }

    /**
     * @return SchemaFeedbackInterface[]
     */
    public function getLogs(): array
    {
        return $this->logs;
    }

    public function addLog(SchemaFeedbackInterface $log): void
    {
        $this->logs[] = $log;
    }

    /**
     * @param SchemaFeedbackInterface[] $logs
     */
    public function setLogs(array $logs): void
    {
        $this->logs = $logs;
    }
}
