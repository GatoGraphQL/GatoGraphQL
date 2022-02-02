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

    public function addQueryError(QueryFeedbackInterface $queryError): void
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

    public function addSchemaError(SchemaFeedbackInterface $schemaError): void
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

    public function addObjectError(ObjectFeedbackInterface $objectError): void
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

    public function addQueryWarning(QueryFeedbackInterface $queryWarning): void
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

    public function addSchemaWarning(SchemaFeedbackInterface $schemaWarning): void
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

    public function addObjectWarning(ObjectFeedbackInterface $objectWarning): void
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

    public function addQueryDeprecation(QueryFeedbackInterface $queryDeprecation): void
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

    public function addSchemaDeprecation(SchemaFeedbackInterface $schemaDeprecation): void
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

    public function addObjectDeprecation(ObjectFeedbackInterface $objectDeprecation): void
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

    public function addQueryNotice(QueryFeedbackInterface $queryNotice): void
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

    public function addSchemaNotice(SchemaFeedbackInterface $schemaNotice): void
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

    public function addObjectNotice(ObjectFeedbackInterface $objectNotice): void
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

    public function addQueryLog(QueryFeedbackInterface $queryLog): void
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

    public function addSchemaLog(SchemaFeedbackInterface $schemaLog): void
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

    public function addObjectLog(ObjectFeedbackInterface $objectLog): void
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

    public function addQueryTrace(QueryFeedbackInterface $queryTrace): void
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

    public function addSchemaTrace(SchemaFeedbackInterface $schemaTrace): void
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

    public function addObjectTrace(ObjectFeedbackInterface $objectTrace): void
    {
        $this->objectTraces[] = $objectTrace;
    }
}
