<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

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
