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
     * @param DocumentFeedbackInterface[] $errors
     */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
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
     * @param DocumentFeedbackInterface[] $warnings
     */
    public function setWarnings(array $warnings): void
    {
        $this->warnings = $warnings;
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
     * @param DocumentFeedbackInterface[] $deprecations
     */
    public function setDeprecations(array $deprecations): void
    {
        $this->deprecations = $deprecations;
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
     * @param DocumentFeedbackInterface[] $notices
     */
    public function setNotices(array $notices): void
    {
        $this->notices = $notices;
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
     * @param DocumentFeedbackInterface[] $logs
     */
    public function setLogs(array $logs): void
    {
        $this->logs = $logs;
    }
}
