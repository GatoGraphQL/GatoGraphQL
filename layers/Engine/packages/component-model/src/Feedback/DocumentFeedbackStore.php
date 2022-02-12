<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

class DocumentFeedbackStore
{
    /** @var DocumentFeedbackInterface[] */
    private array $errors = [];
    /** @var DocumentFeedbackInterface[] */
    private array $warnings = [];
    /** @var DocumentFeedbackInterface[] */
    private array $deprecations = [];
    /** @var DocumentFeedbackInterface[] */
    private array $notices = [];
    /** @var DocumentFeedbackInterface[] */
    private array $logs = [];
    /** @var DocumentFeedbackInterface[] */
    private array $traces = [];

    /**
     * @return DocumentFeedbackInterface[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function addError(DocumentFeedbackInterface $error): void
    {
        $this->errors[] = $error;
    }

    /**
     * @return DocumentFeedbackInterface[]
     */
    public function getWarnings(): array
    {
        return $this->warnings;
    }

    public function addWarning(DocumentFeedbackInterface $warning): void
    {
        $this->warnings[] = $warning;
    }

    /**
     * @return DocumentFeedbackInterface[]
     */
    public function getDeprecations(): array
    {
        return $this->deprecations;
    }

    public function addDeprecation(DocumentFeedbackInterface $deprecation): void
    {
        $this->deprecations[] = $deprecation;
    }

    /**
     * @return DocumentFeedbackInterface[]
     */
    public function getNotices(): array
    {
        return $this->notices;
    }

    public function addNotice(DocumentFeedbackInterface $notice): void
    {
        $this->notices[] = $notice;
    }

    /**
     * @return DocumentFeedbackInterface[]
     */
    public function getLogs(): array
    {
        return $this->logs;
    }

    public function addLog(DocumentFeedbackInterface $log): void
    {
        $this->logs[] = $log;
    }

    /**
     * @return DocumentFeedbackInterface[]
     */
    public function getTraces(): array
    {
        return $this->traces;
    }

    public function addTrace(DocumentFeedbackInterface $trace): void
    {
        $this->traces[] = $trace;
    }
}
