<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

class GeneralFeedbackStore
{
    /** @var GeneralFeedbackInterface[] */
    private array $errors = [];
    /** @var GeneralFeedbackInterface[] */
    private array $warnings = [];
    /** @var GeneralFeedbackInterface[] */
    private array $deprecations = [];
    /** @var GeneralFeedbackInterface[] */
    private array $notices = [];
    /** @var GeneralFeedbackInterface[] */
    private array $logs = [];
    /** @var TraceInterface[] */
    private array $traces = [];

    /**
     * @return GeneralFeedbackInterface[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function addError(GeneralFeedbackInterface $error): void
    {
        $this->errors[] = $error;
    }

    /**
     * @param GeneralFeedbackInterface[] $errors
     */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }

    /**
     * @return GeneralFeedbackInterface[]
     */
    public function getWarnings(): array
    {
        return $this->warnings;
    }

    public function addWarning(GeneralFeedbackInterface $warning): void
    {
        $this->warnings[] = $warning;
    }

    /**
     * @param GeneralFeedbackInterface[] $warnings
     */
    public function setWarnings(array $warnings): void
    {
        $this->warnings = $warnings;
    }

    /**
     * @return GeneralFeedbackInterface[]
     */
    public function getDeprecations(): array
    {
        return $this->deprecations;
    }

    public function addDeprecation(GeneralFeedbackInterface $deprecation): void
    {
        $this->deprecations[] = $deprecation;
    }

    /**
     * @param GeneralFeedbackInterface[] $deprecations
     */
    public function setDeprecations(array $deprecations): void
    {
        $this->deprecations = $deprecations;
    }

    /**
     * @return GeneralFeedbackInterface[]
     */
    public function getNotices(): array
    {
        return $this->notices;
    }

    public function addNotice(GeneralFeedbackInterface $notice): void
    {
        $this->notices[] = $notice;
    }

    /**
     * @param GeneralFeedbackInterface[] $notices
     */
    public function setNotices(array $notices): void
    {
        $this->notices = $notices;
    }

    /**
     * @return GeneralFeedbackInterface[]
     */
    public function getLogs(): array
    {
        return $this->logs;
    }

    public function addLog(GeneralFeedbackInterface $log): void
    {
        $this->logs[] = $log;
    }

    /**
     * @param GeneralFeedbackInterface[] $logs
     */
    public function setLogs(array $logs): void
    {
        $this->logs = $logs;
    }

    /**
     * @return TraceInterface[]
     */
    public function getTraces(): array
    {
        return $this->traces;
    }

    public function addTrace(TraceInterface $trace): void
    {
        $this->traces[] = $trace;
    }

    /**
     * @param TraceInterface[] $traces
     */
    public function setTraces(array $traces): void
    {
        $this->traces = $traces;
    }
}
