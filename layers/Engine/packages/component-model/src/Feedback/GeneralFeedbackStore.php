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
    /** @var GeneralFeedbackInterface[] */
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
     * @return GeneralFeedbackInterface[]
     */
    public function getTraces(): array
    {
        return $this->traces;
    }

    public function addTrace(GeneralFeedbackInterface $trace): void
    {
        $this->traces[] = $trace;
    }
}
