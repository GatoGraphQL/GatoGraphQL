<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

class ObjectFeedbackStore
{
    /** @var ObjectFeedbackInterface[] */
    private array $errors = [];
    /** @var ObjectFeedbackInterface[] */
    private array $warnings = [];
    /** @var ObjectFeedbackInterface[] */
    private array $deprecations = [];
    /** @var ObjectFeedbackInterface[] */
    private array $notices = [];
    /** @var ObjectFeedbackInterface[] */
    private array $suggestions = [];
    /** @var ObjectFeedbackInterface[] */
    private array $logs = [];

    public function incorporate(
        ObjectFeedbackStore $objectFeedbackStore,
    ): void {
        $this->errors = array_merge(
            $this->errors,
            $objectFeedbackStore->getErrors()
        );
        $this->warnings = array_merge(
            $this->warnings,
            $objectFeedbackStore->getWarnings()
        );
        $this->deprecations = array_merge(
            $this->deprecations,
            $objectFeedbackStore->getDeprecations()
        );
        $this->notices = array_merge(
            $this->notices,
            $objectFeedbackStore->getNotices()
        );
        $this->suggestions = array_merge(
            $this->suggestions,
            $objectFeedbackStore->getSuggestions()
        );
        $this->logs = array_merge(
            $this->logs,
            $objectFeedbackStore->getLogs()
        );
    }

    public function incorporateFromObjectTypeFieldResolutionFeedbackStore(
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $field,
        string|int $objectID,
        ?string $directive = null,
    ): void {
        foreach ($objectTypeFieldResolutionFeedbackStore->getErrors() as $objectTypeFieldResolutionFeedbackError) {
            $this->errors[] = ObjectFeedback::fromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedbackError,
                $relationalTypeResolver,
                $field,
                $objectID,
                $directive,
            );
        }
        foreach ($objectTypeFieldResolutionFeedbackStore->getWarnings() as $objectTypeFieldResolutionFeedbackWarning) {
            $this->warnings[] = ObjectFeedback::fromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedbackWarning,
                $relationalTypeResolver,
                $field,
                $objectID,
                $directive,
            );
        }
        foreach ($objectTypeFieldResolutionFeedbackStore->getDeprecations() as $objectTypeFieldResolutionFeedbackDeprecation) {
            $this->deprecations[] = ObjectFeedback::fromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedbackDeprecation,
                $relationalTypeResolver,
                $field,
                $objectID,
                $directive,
            );
        }
        foreach ($objectTypeFieldResolutionFeedbackStore->getNotices() as $objectTypeFieldResolutionFeedbackNotice) {
            $this->notices[] = ObjectFeedback::fromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedbackNotice,
                $relationalTypeResolver,
                $field,
                $objectID,
                $directive,
            );
        }
        foreach ($objectTypeFieldResolutionFeedbackStore->getSuggestions() as $objectTypeFieldResolutionFeedbackSuggestion) {
            $this->suggestions[] = ObjectFeedback::fromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedbackSuggestion,
                $relationalTypeResolver,
                $field,
                $objectID,
                $directive,
            );
        }
        foreach ($objectTypeFieldResolutionFeedbackStore->getLogs() as $objectTypeFieldResolutionFeedbackLog) {
            $this->logs[] = ObjectFeedback::fromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedbackLog,
                $relationalTypeResolver,
                $field,
                $objectID,
                $directive,
            );
        }
    }

    /**
     * @return ObjectFeedbackInterface[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function addError(ObjectFeedbackInterface $error): void
    {
        $this->errors[] = $error;
    }

    /**
     * @param ObjectFeedbackInterface[] $errors
     */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }

    /**
     * @return ObjectFeedbackInterface[]
     */
    public function getWarnings(): array
    {
        return $this->warnings;
    }

    public function addWarning(ObjectFeedbackInterface $warning): void
    {
        $this->warnings[] = $warning;
    }

    /**
     * @param ObjectFeedbackInterface[] $warnings
     */
    public function setWarnings(array $warnings): void
    {
        $this->warnings = $warnings;
    }

    /**
     * @return ObjectFeedbackInterface[]
     */
    public function getDeprecations(): array
    {
        return $this->deprecations;
    }

    public function addDeprecation(ObjectFeedbackInterface $deprecation): void
    {
        $this->deprecations[] = $deprecation;
    }

    /**
     * @param ObjectFeedbackInterface[] $deprecations
     */
    public function setDeprecations(array $deprecations): void
    {
        $this->deprecations = $deprecations;
    }

    /**
     * @return ObjectFeedbackInterface[]
     */
    public function getNotices(): array
    {
        return $this->notices;
    }

    public function addNotice(ObjectFeedbackInterface $notice): void
    {
        $this->notices[] = $notice;
    }

    /**
     * @param ObjectFeedbackInterface[] $notices
     */
    public function setNotices(array $notices): void
    {
        $this->notices = $notices;
    }

    /**
     * @return ObjectFeedbackInterface[]
     */
    public function getSuggestions(): array
    {
        return $this->suggestions;
    }

    public function addSuggestion(ObjectFeedbackInterface $suggestion): void
    {
        $this->suggestions[] = $suggestion;
    }

    /**
     * @param ObjectFeedbackInterface[] $suggestions
     */
    public function setSuggestions(array $suggestions): void
    {
        $this->suggestions = $suggestions;
    }

    /**
     * @return ObjectFeedbackInterface[]
     */
    public function getLogs(): array
    {
        return $this->logs;
    }

    public function addLog(ObjectFeedbackInterface $log): void
    {
        $this->logs[] = $log;
    }

    /**
     * @param ObjectFeedbackInterface[] $logs
     */
    public function setLogs(array $logs): void
    {
        $this->logs = $logs;
    }
}
