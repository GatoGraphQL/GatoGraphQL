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
    private array $logs = [];
    /** @var ObjectFeedbackInterface[] */
    private array $traces = [];

    public function incorporate(
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $field,
        string|int $objectID,
    ): void {
        foreach ($objectTypeFieldResolutionFeedbackStore->getErrors() as $fieldResolutionError) {
            $this->errors[] = ObjectFeedback::fromObjectTypeFieldResolutionFeedback(
                $fieldResolutionError,
                $relationalTypeResolver,
                $field,
                $objectID,
            );
        }
        foreach ($objectTypeFieldResolutionFeedbackStore->getWarnings() as $fieldResolutionWarning) {
            $this->warnings[] = ObjectFeedback::fromObjectTypeFieldResolutionFeedback(
                $fieldResolutionWarning,
                $relationalTypeResolver,
                $field,
                $objectID,
            );
        }
        foreach ($objectTypeFieldResolutionFeedbackStore->getDeprecations() as $fieldResolutionDeprecation) {
            $this->deprecations[] = ObjectFeedback::fromObjectTypeFieldResolutionFeedback(
                $fieldResolutionDeprecation,
                $relationalTypeResolver,
                $field,
                $objectID,
            );
        }
        foreach ($objectTypeFieldResolutionFeedbackStore->getNotices() as $fieldResolutionNotice) {
            $this->notices[] = ObjectFeedback::fromObjectTypeFieldResolutionFeedback(
                $fieldResolutionNotice,
                $relationalTypeResolver,
                $field,
                $objectID,
            );
        }
        foreach ($objectTypeFieldResolutionFeedbackStore->getLogs() as $fieldResolutionLog) {
            $this->logs[] = ObjectFeedback::fromObjectTypeFieldResolutionFeedback(
                $fieldResolutionLog,
                $relationalTypeResolver,
                $field,
                $objectID,
            );
        }
        foreach ($objectTypeFieldResolutionFeedbackStore->getTraces() as $fieldResolutionTrace) {
            $this->traces[] = ObjectFeedback::fromObjectTypeFieldResolutionFeedback(
                $fieldResolutionTrace,
                $relationalTypeResolver,
                $field,
                $objectID,
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
     * @return ObjectFeedbackInterface[]
     */
    public function getTraces(): array
    {
        return $this->traces;
    }

    public function addTrace(ObjectFeedbackInterface $trace): void
    {
        $this->traces[] = $trace;
    }
}
