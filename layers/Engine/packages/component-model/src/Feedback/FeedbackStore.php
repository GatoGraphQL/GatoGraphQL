<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

class FeedbackStore
{
    /** @var QueryFeedbackInterface[] */
    private array $queryErrors = [];
    /** @var SchemaFeedbackInterface[] */
    private array $schemaErrors = [];
    /** @var ObjectFeedbackInterface[] */
    private array $objectErrors = [];
    /** @var QueryFeedbackInterface[] */
    private array $queryWarnings = [];
    /** @var SchemaFeedbackInterface[] */
    private array $schemaWarnings = [];
    /** @var ObjectFeedbackInterface[] */
    private array $objectWarnings = [];
    /** @var QueryFeedbackInterface[] */
    private array $queryDeprecations = [];
    /** @var SchemaFeedbackInterface[] */
    private array $schemaDeprecations = [];
    /** @var ObjectFeedbackInterface[] */
    private array $objectDeprecations = [];
    /** @var QueryFeedbackInterface[] */
    private array $queryNotices = [];
    /** @var SchemaFeedbackInterface[] */
    private array $schemaNotices = [];
    /** @var ObjectFeedbackInterface[] */
    private array $objectNotices = [];
    /** @var QueryFeedbackInterface[] */
    private array $queryLogs = [];
    /** @var SchemaFeedbackInterface[] */
    private array $schemaLogs = [];
    /** @var ObjectFeedbackInterface[] */
    private array $objectLogs = [];
    /** @var QueryFeedbackInterface[] */
    private array $queryTraces = [];
    /** @var SchemaFeedbackInterface[] */
    private array $schemaTraces = [];
    /** @var ObjectFeedbackInterface[] */
    private array $objectTraces = [];

    /**
     * @return QueryFeedbackInterface[]
     */
    public function getQueryErrors(): array
    {
        return $this->queryErrors;
    }

    /**
     * @param array $queryError
     */
    public function addQueryError(array $queryError): void
    {
        $this->queryErrors[] = $queryError;
    }

    /**
     * @return SchemaFeedbackInterface[]
     */
    public function getSchemaErrors(): array
    {
        return $this->schemaErrors;
    }

    /**
     * @param array $schemaError
     */
    public function addSchemaError(array $schemaError): void
    {
        $this->schemaErrors[] = $schemaError;
    }

    /**
     * @return ObjectFeedbackInterface[]
     */
    public function getObjectErrors(): array
    {
        return $this->objectErrors;
    }

    /**
     * @param array $objectError
     */
    public function addObjectError(array $objectError): void
    {
        $this->objectErrors[] = $objectError;
    }

    /**
     * @return QueryFeedbackInterface[]
     */
    public function getQueryWarnings(): array
    {
        return $this->queryWarnings;
    }

    /**
     * @param array $queryWarning
     */
    public function addQueryWarning(array $queryWarning): void
    {
        $this->queryWarnings[] = $queryWarning;
    }

    /**
     * @return SchemaFeedbackInterface[]
     */
    public function getSchemaWarnings(): array
    {
        return $this->schemaWarnings;
    }

    /**
     * @param array $schemaWarning
     */
    public function addSchemaWarning(array $schemaWarning): void
    {
        $this->schemaWarnings[] = $schemaWarning;
    }

    /**
     * @return ObjectFeedbackInterface[]
     */
    public function getObjectWarnings(): array
    {
        return $this->objectWarnings;
    }

    /**
     * @param array $objectWarning
     */
    public function addObjectWarning(array $objectWarning): void
    {
        $this->objectWarnings[] = $objectWarning;
    }

    /**
     * @return QueryFeedbackInterface[]
     */
    public function getQueryDeprecations(): array
    {
        return $this->queryDeprecations;
    }

    /**
     * @param array $queryDeprecation
     */
    public function addQueryDeprecation(array $queryDeprecation): void
    {
        $this->queryDeprecations[] = $queryDeprecation;
    }

    /**
     * @return SchemaFeedbackInterface[]
     */
    public function getSchemaDeprecations(): array
    {
        return $this->schemaDeprecations;
    }

    /**
     * @param array $schemaDeprecation
     */
    public function addSchemaDeprecation(array $schemaDeprecation): void
    {
        $this->schemaDeprecations[] = $schemaDeprecation;
    }

    /**
     * @return ObjectFeedbackInterface[]
     */
    public function getObjectDeprecations(): array
    {
        return $this->objectDeprecations;
    }

    /**
     * @param array $objectDeprecation
     */
    public function addObjectDeprecation(array $objectDeprecation): void
    {
        $this->objectDeprecations[] = $objectDeprecation;
    }

    /**
     * @return QueryFeedbackInterface[]
     */
    public function getQueryNotices(): array
    {
        return $this->queryNotices;
    }

    /**
     * @param array $queryNotice
     */
    public function addQueryNotice(array $queryNotice): void
    {
        $this->queryNotices[] = $queryNotice;
    }

    /**
     * @return SchemaFeedbackInterface[]
     */
    public function getSchemaNotices(): array
    {
        return $this->schemaNotices;
    }

    /**
     * @param array $schemaNotice
     */
    public function addSchemaNotice(array $schemaNotice): void
    {
        $this->schemaNotices[] = $schemaNotice;
    }

    /**
     * @return ObjectFeedbackInterface[]
     */
    public function getObjectNotices(): array
    {
        return $this->objectNotices;
    }

    /**
     * @param array $objectNotice
     */
    public function addObjectNotice(array $objectNotice): void
    {
        $this->objectNotices[] = $objectNotice;
    }

    /**
     * @return QueryFeedbackInterface[]
     */
    public function getQueryLogs(): array
    {
        return $this->queryLogs;
    }

    /**
     * @param array $queryLog
     */
    public function addQueryLog(array $queryLog): void
    {
        $this->queryLogs[] = $queryLog;
    }

    /**
     * @return SchemaFeedbackInterface[]
     */
    public function getSchemaLogs(): array
    {
        return $this->schemaLogs;
    }

    /**
     * @param array $schemaLog
     */
    public function addSchemaLog(array $schemaLog): void
    {
        $this->schemaLogs[] = $schemaLog;
    }

    /**
     * @return ObjectFeedbackInterface[]
     */
    public function getObjectLogs(): array
    {
        return $this->objectLogs;
    }

    /**
     * @param array $objectLog
     */
    public function addObjectLog(array $objectLog): void
    {
        $this->objectLogs[] = $objectLog;
    }

    /**
     * @return QueryFeedbackInterface[]
     */
    public function getQueryTraces(): array
    {
        return $this->queryTraces;
    }

    /**
     * @param array $queryTrace
     */
    public function addQueryTrace(array $queryTrace): void
    {
        $this->queryTraces[] = $queryTrace;
    }

    /**
     * @return SchemaFeedbackInterface[]
     */
    public function getSchemaTraces(): array
    {
        return $this->schemaTraces;
    }

    /**
     * @param array $schemaTrace
     */
    public function addSchemaTrace(array $schemaTrace): void
    {
        $this->schemaTraces[] = $schemaTrace;
    }

    /**
     * @return ObjectFeedbackInterface[]
     */
    public function getObjectTraces(): array
    {
        return $this->objectTraces;
    }

    /**
     * @param array $objectTrace
     */
    public function addObjectTrace(array $objectTrace): void
    {
        $this->objectTraces[] = $objectTrace;
    }
}
