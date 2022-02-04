<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

class DocumentFeedbackStore
{
    /** @var DocumentFeedbackInterface[] */
    private array $documentErrors = [];
    /** @var DocumentFeedbackInterface[] */
    private array $documentWarnings = [];
    /** @var DocumentFeedbackInterface[] */
    private array $documentDeprecations = [];
    /** @var DocumentFeedbackInterface[] */
    private array $documentNotices = [];
    /** @var DocumentFeedbackInterface[] */
    private array $documentLogs = [];
    /** @var DocumentFeedbackInterface[] */
    private array $documentTraces = [];

    /**
     * @return DocumentFeedbackInterface[]
     */
    public function getDocumentErrors(): array
    {
        return $this->documentErrors;
    }

    public function addDocumentError(DocumentFeedbackInterface $documentError): void
    {
        $this->documentErrors[] = $documentError;
    }

    /**
     * @return DocumentFeedbackInterface[]
     */
    public function getDocumentWarnings(): array
    {
        return $this->documentWarnings;
    }

    public function addDocumentWarning(DocumentFeedbackInterface $documentWarning): void
    {
        $this->documentWarnings[] = $documentWarning;
    }

    /**
     * @return DocumentFeedbackInterface[]
     */
    public function getDocumentDeprecations(): array
    {
        return $this->documentDeprecations;
    }

    public function addDocumentDeprecation(DocumentFeedbackInterface $documentDeprecation): void
    {
        $this->documentDeprecations[] = $documentDeprecation;
    }

    /**
     * @return DocumentFeedbackInterface[]
     */
    public function getDocumentNotices(): array
    {
        return $this->documentNotices;
    }

    public function addDocumentNotice(DocumentFeedbackInterface $documentNotice): void
    {
        $this->documentNotices[] = $documentNotice;
    }

    /**
     * @return DocumentFeedbackInterface[]
     */
    public function getDocumentLogs(): array
    {
        return $this->documentLogs;
    }

    public function addDocumentLog(DocumentFeedbackInterface $documentLog): void
    {
        $this->documentLogs[] = $documentLog;
    }

    /**
     * @return DocumentFeedbackInterface[]
     */
    public function getDocumentTraces(): array
    {
        return $this->documentTraces;
    }

    public function addDocumentTrace(DocumentFeedbackInterface $documentTrace): void
    {
        $this->documentTraces[] = $documentTrace;
    }
}
