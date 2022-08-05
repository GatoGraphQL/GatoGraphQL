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
    private array $suggestions = [];
    /** @var GeneralFeedbackInterface[] */
    private array $logs = [];

    public function getErrorCount(): int
    {
        return count($this->getErrors());
    }

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
    public function getSuggestions(): array
    {
        return $this->suggestions;
    }

    public function addSuggestion(GeneralFeedbackInterface $suggestion): void
    {
        $this->suggestions[] = $suggestion;
    }

    /**
     * @param GeneralFeedbackInterface[] $suggestions
     */
    public function setSuggestions(array $suggestions): void
    {
        $this->suggestions = $suggestions;
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
}
