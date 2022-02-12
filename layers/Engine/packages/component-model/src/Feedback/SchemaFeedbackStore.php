<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

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
    private array $logs = [];
    /** @var SchemaFeedbackInterface[] */
    private array $traces = [];

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
     * @return SchemaFeedbackInterface[]
     */
    public function getTraces(): array
    {
        return $this->traces;
    }

    public function addTrace(SchemaFeedbackInterface $trace): void
    {
        $this->traces[] = $trace;
    }
}
