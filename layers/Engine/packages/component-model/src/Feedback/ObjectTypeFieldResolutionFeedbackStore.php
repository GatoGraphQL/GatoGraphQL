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

    /**
     * @param ObjectTypeFieldResolutionFeedbackInterface[] $traces
     */
    public function setTraces(array $traces): void
    {
        $this->traces = $traces;
    }
}
