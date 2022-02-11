<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

class ObjectTypeFieldResolutionFeedbackStore
{
    /** @var ObjectTypeFieldResolutionFeedbackInterface[] */
    private array $objectTypeFieldResolutionErrors = [];
    /** @var ObjectTypeFieldResolutionFeedbackInterface[] */
    private array $objectTypeFieldResolutionWarnings = [];
    /** @var ObjectTypeFieldResolutionFeedbackInterface[] */
    private array $objectTypeFieldResolutionDeprecations = [];
    /** @var ObjectTypeFieldResolutionFeedbackInterface[] */
    private array $objectTypeFieldResolutionNotices = [];
    /** @var ObjectTypeFieldResolutionFeedbackInterface[] */
    private array $objectTypeFieldResolutionLogs = [];
    /** @var ObjectTypeFieldResolutionFeedbackInterface[] */
    private array $objectTypeFieldResolutionTraces = [];

    /**
     * @return ObjectTypeFieldResolutionFeedbackInterface[]
     */
    public function getFieldResolutionErrors(): array
    {
        return $this->objectTypeFieldResolutionErrors;
    }

    public function addFieldResolutionError(ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionError): void
    {
        $this->objectTypeFieldResolutionErrors[] = $objectTypeFieldResolutionError;
    }

    /**
     * @return ObjectTypeFieldResolutionFeedbackInterface[]
     */
    public function getFieldResolutionWarnings(): array
    {
        return $this->objectTypeFieldResolutionWarnings;
    }

    public function addFieldResolutionWarning(ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionWarning): void
    {
        $this->objectTypeFieldResolutionWarnings[] = $objectTypeFieldResolutionWarning;
    }

    /**
     * @return ObjectTypeFieldResolutionFeedbackInterface[]
     */
    public function getFieldResolutionDeprecations(): array
    {
        return $this->objectTypeFieldResolutionDeprecations;
    }

    public function addFieldResolutionDeprecation(ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionDeprecation): void
    {
        $this->objectTypeFieldResolutionDeprecations[] = $objectTypeFieldResolutionDeprecation;
    }

    /**
     * @return ObjectTypeFieldResolutionFeedbackInterface[]
     */
    public function getFieldResolutionNotices(): array
    {
        return $this->objectTypeFieldResolutionNotices;
    }

    public function addFieldResolutionNotice(ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionNotice): void
    {
        $this->objectTypeFieldResolutionNotices[] = $objectTypeFieldResolutionNotice;
    }

    /**
     * @return ObjectTypeFieldResolutionFeedbackInterface[]
     */
    public function getFieldResolutionLogs(): array
    {
        return $this->objectTypeFieldResolutionLogs;
    }

    public function addFieldResolutionLog(ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionLog): void
    {
        $this->objectTypeFieldResolutionLogs[] = $objectTypeFieldResolutionLog;
    }

    /**
     * @return ObjectTypeFieldResolutionFeedbackInterface[]
     */
    public function getFieldResolutionTraces(): array
    {
        return $this->objectTypeFieldResolutionTraces;
    }

    public function addFieldResolutionTrace(ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionTrace): void
    {
        $this->objectTypeFieldResolutionTraces[] = $objectTypeFieldResolutionTrace;
    }
}
