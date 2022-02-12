<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

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
    private array $logs = [];
    /** @var ObjectTypeFieldResolutionFeedbackInterface[] */
    private array $traces = [];

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
     * @return ObjectTypeFieldResolutionFeedbackInterface[]
     */
    public function getTraces(): array
    {
        return $this->traces;
    }

    public function addTrace(ObjectTypeFieldResolutionFeedbackInterface $trace): void
    {
        $this->traces[] = $trace;
    }
}
