<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

class ObjectFeedbackStore
{
    /** @var ObjectFeedbackInterface[] */
    private array $objectErrors = [];
    /** @var ObjectFeedbackInterface[] */
    private array $objectWarnings = [];
    /** @var ObjectFeedbackInterface[] */
    private array $objectDeprecations = [];
    /** @var ObjectFeedbackInterface[] */
    private array $objectNotices = [];
    /** @var ObjectFeedbackInterface[] */
    private array $objectLogs = [];
    /** @var ObjectFeedbackInterface[] */
    private array $objectTraces = [];

    /**
     * @return ObjectFeedbackInterface[]
     */
    public function getObjectErrors(): array
    {
        return $this->objectErrors;
    }

    public function addObjectError(ObjectFeedbackInterface $objectError): void
    {
        $this->objectErrors[] = $objectError;
    }

    /**
     * @return ObjectFeedbackInterface[]
     */
    public function getObjectWarnings(): array
    {
        return $this->objectWarnings;
    }

    public function addObjectWarning(ObjectFeedbackInterface $objectWarning): void
    {
        $this->objectWarnings[] = $objectWarning;
    }

    /**
     * @return ObjectFeedbackInterface[]
     */
    public function getObjectDeprecations(): array
    {
        return $this->objectDeprecations;
    }

    public function addObjectDeprecation(ObjectFeedbackInterface $objectDeprecation): void
    {
        $this->objectDeprecations[] = $objectDeprecation;
    }

    /**
     * @return ObjectFeedbackInterface[]
     */
    public function getObjectNotices(): array
    {
        return $this->objectNotices;
    }

    public function addObjectNotice(ObjectFeedbackInterface $objectNotice): void
    {
        $this->objectNotices[] = $objectNotice;
    }

    /**
     * @return ObjectFeedbackInterface[]
     */
    public function getObjectLogs(): array
    {
        return $this->objectLogs;
    }

    public function addObjectLog(ObjectFeedbackInterface $objectLog): void
    {
        $this->objectLogs[] = $objectLog;
    }

    /**
     * @return ObjectFeedbackInterface[]
     */
    public function getObjectTraces(): array
    {
        return $this->objectTraces;
    }

    public function addObjectTrace(ObjectFeedbackInterface $objectTrace): void
    {
        $this->objectTraces[] = $objectTrace;
    }
}
