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
        foreach ($objectTypeFieldResolutionFeedbackStore->getErrors() as $objectTypeFieldResolutionFeedbackError) {
            $this->errors[] = ObjectFeedback::fromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedbackError,
                $relationalTypeResolver,
                $field,
                $objectID,
            );
        }
        foreach ($objectTypeFieldResolutionFeedbackStore->getWarnings() as $objectTypeFieldResolutionFeedbackWarning) {
            $this->warnings[] = ObjectFeedback::fromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedbackWarning,
                $relationalTypeResolver,
                $field,
                $objectID,
            );
        }
        foreach ($objectTypeFieldResolutionFeedbackStore->getDeprecations() as $objectTypeFieldResolutionFeedbackDeprecation) {
            $this->deprecations[] = ObjectFeedback::fromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedbackDeprecation,
                $relationalTypeResolver,
                $field,
                $objectID,
            );
        }
        foreach ($objectTypeFieldResolutionFeedbackStore->getNotices() as $objectTypeFieldResolutionFeedbackNotice) {
            $this->notices[] = ObjectFeedback::fromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedbackNotice,
                $relationalTypeResolver,
                $field,
                $objectID,
            );
        }
        foreach ($objectTypeFieldResolutionFeedbackStore->getLogs() as $objectTypeFieldResolutionFeedbackLog) {
            $this->logs[] = ObjectFeedback::fromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedbackLog,
                $relationalTypeResolver,
                $field,
                $objectID,
            );
        }
        foreach ($objectTypeFieldResolutionFeedbackStore->getTraces() as $objectTypeFieldResolutionFeedbackTrace) {
            $this->traces[] = ObjectFeedback::fromObjectTypeFieldResolutionFeedback(
                $objectTypeFieldResolutionFeedbackTrace,
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
