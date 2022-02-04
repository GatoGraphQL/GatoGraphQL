<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

class GeneralFeedbackStore
{
    /** @var GeneralFeedbackInterface[] */
    private array $generalErrors = [];
    /** @var GeneralFeedbackInterface[] */
    private array $generalWarnings = [];
    /** @var GeneralFeedbackInterface[] */
    private array $generalDeprecations = [];
    /** @var GeneralFeedbackInterface[] */
    private array $generalNotices = [];
    /** @var GeneralFeedbackInterface[] */
    private array $generalLogs = [];
    /** @var GeneralFeedbackInterface[] */
    private array $generalTraces = [];

    /**
     * @return GeneralFeedbackInterface[]
     */
    public function getGeneralErrors(): array
    {
        return $this->generalErrors;
    }

    public function addGeneralError(GeneralFeedbackInterface $generalError): void
    {
        $this->generalErrors[] = $generalError;
    }

    /**
     * @return GeneralFeedbackInterface[]
     */
    public function getGeneralWarnings(): array
    {
        return $this->generalWarnings;
    }

    public function addGeneralWarning(GeneralFeedbackInterface $generalWarning): void
    {
        $this->generalWarnings[] = $generalWarning;
    }

    /**
     * @return GeneralFeedbackInterface[]
     */
    public function getGeneralDeprecations(): array
    {
        return $this->generalDeprecations;
    }

    public function addGeneralDeprecation(GeneralFeedbackInterface $generalDeprecation): void
    {
        $this->generalDeprecations[] = $generalDeprecation;
    }

    /**
     * @return GeneralFeedbackInterface[]
     */
    public function getGeneralNotices(): array
    {
        return $this->generalNotices;
    }

    public function addGeneralNotice(GeneralFeedbackInterface $generalNotice): void
    {
        $this->generalNotices[] = $generalNotice;
    }

    /**
     * @return GeneralFeedbackInterface[]
     */
    public function getGeneralLogs(): array
    {
        return $this->generalLogs;
    }

    public function addGeneralLog(GeneralFeedbackInterface $generalLog): void
    {
        $this->generalLogs[] = $generalLog;
    }

    /**
     * @return GeneralFeedbackInterface[]
     */
    public function getGeneralTraces(): array
    {
        return $this->generalTraces;
    }

    public function addGeneralTrace(GeneralFeedbackInterface $generalTrace): void
    {
        $this->generalTraces[] = $generalTrace;
    }
}
