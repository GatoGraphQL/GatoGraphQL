<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

class QueryFeedbackStore
{
    /** @var QueryFeedbackInterface[] */
    private array $queryErrors = [];
    /** @var QueryFeedbackInterface[] */
    private array $queryWarnings = [];
    /** @var QueryFeedbackInterface[] */
    private array $queryDeprecations = [];
    /** @var QueryFeedbackInterface[] */
    private array $queryNotices = [];
    /** @var QueryFeedbackInterface[] */
    private array $queryLogs = [];
    /** @var QueryFeedbackInterface[] */
    private array $queryTraces = [];
    
    /**
     * @return QueryFeedbackInterface[]
     */
    public function getQueryErrors(): array
    {
        return $this->queryErrors;
    }

    public function addQueryError(QueryFeedbackInterface $queryError): void
    {
        $this->queryErrors[] = $queryError;
    }

    /**
     * @return QueryFeedbackInterface[]
     */
    public function getQueryWarnings(): array
    {
        return $this->queryWarnings;
    }

    public function addQueryWarning(QueryFeedbackInterface $queryWarning): void
    {
        $this->queryWarnings[] = $queryWarning;
    }

    /**
     * @return QueryFeedbackInterface[]
     */
    public function getQueryDeprecations(): array
    {
        return $this->queryDeprecations;
    }

    public function addQueryDeprecation(QueryFeedbackInterface $queryDeprecation): void
    {
        $this->queryDeprecations[] = $queryDeprecation;
    }

    /**
     * @return QueryFeedbackInterface[]
     */
    public function getQueryNotices(): array
    {
        return $this->queryNotices;
    }

    public function addQueryNotice(QueryFeedbackInterface $queryNotice): void
    {
        $this->queryNotices[] = $queryNotice;
    }

    /**
     * @return QueryFeedbackInterface[]
     */
    public function getQueryLogs(): array
    {
        return $this->queryLogs;
    }

    public function addQueryLog(QueryFeedbackInterface $queryLog): void
    {
        $this->queryLogs[] = $queryLog;
    }

    /**
     * @return QueryFeedbackInterface[]
     */
    public function getQueryTraces(): array
    {
        return $this->queryTraces;
    }

    public function addQueryTrace(QueryFeedbackInterface $queryTrace): void
    {
        $this->queryTraces[] = $queryTrace;
    }
}
