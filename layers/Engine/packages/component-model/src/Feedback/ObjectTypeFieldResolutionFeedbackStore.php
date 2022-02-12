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
    public function getObjectTypeFieldResolutionErrors(): array
    {
        return $this->objectTypeFieldResolutionErrors;
    }

    public function addObjectTypeFieldResolutionError(ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionError): void
    {
        $this->objectTypeFieldResolutionErrors[] = $objectTypeFieldResolutionError;
    }

    /**
     * @return ObjectTypeFieldResolutionFeedbackInterface[]
     */
    public function getObjectTypeFieldResolutionWarnings(): array
    {
        return $this->objectTypeFieldResolutionWarnings;
    }

    public function addObjectTypeFieldResolutionWarning(ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionWarning): void
    {
        $this->objectTypeFieldResolutionWarnings[] = $objectTypeFieldResolutionWarning;
    }

    /**
     * @return ObjectTypeFieldResolutionFeedbackInterface[]
     */
    public function getObjectTypeFieldResolutionDeprecations(): array
    {
        return $this->objectTypeFieldResolutionDeprecations;
    }

    public function addObjectTypeFieldResolutionDeprecation(ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionDeprecation): void
    {
        $this->objectTypeFieldResolutionDeprecations[] = $objectTypeFieldResolutionDeprecation;
    }

    /**
     * @return ObjectTypeFieldResolutionFeedbackInterface[]
     */
    public function getObjectTypeFieldResolutionNotices(): array
    {
        return $this->objectTypeFieldResolutionNotices;
    }

    public function addObjectTypeFieldResolutionNotice(ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionNotice): void
    {
        $this->objectTypeFieldResolutionNotices[] = $objectTypeFieldResolutionNotice;
    }

    /**
     * @return ObjectTypeFieldResolutionFeedbackInterface[]
     */
    public function getObjectTypeFieldResolutionLogs(): array
    {
        return $this->objectTypeFieldResolutionLogs;
    }

    public function addObjectTypeFieldResolutionLog(ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionLog): void
    {
        $this->objectTypeFieldResolutionLogs[] = $objectTypeFieldResolutionLog;
    }

    /**
     * @return ObjectTypeFieldResolutionFeedbackInterface[]
     */
    public function getObjectTypeFieldResolutionTraces(): array
    {
        return $this->objectTypeFieldResolutionTraces;
    }

    public function addObjectTypeFieldResolutionTrace(ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionTrace): void
    {
        $this->objectTypeFieldResolutionTraces[] = $objectTypeFieldResolutionTrace;
    }
}
