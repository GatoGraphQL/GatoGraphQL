<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

class FeedbackStore
{
    private array $queryErrors = [];
    private array $schemaErrors = [];
    private array $objectErrors = [];
    private array $queryWarnings = [];
    private array $schemaWarnings = [];
    private array $objectWarnings = [];
    private array $queryDeprecations = [];
    private array $schemaDeprecations = [];
    private array $objectDeprecations = [];
    private array $queryNotices = [];
    private array $schemaNotices = [];
    private array $objectNotices = [];
    private array $queryLogs = [];
    private array $schemaLogs = [];
    private array $objectLogs = [];
    private array $queryTraces = [];
    private array $schemaTraces = [];
    private array $objectTraces = [];

    /**
     * @return array
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
     * @return array
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
     * @return array
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
     * @return array
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
     * @return array
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
     * @return array
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
     * @return array
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
     * @return array
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
     * @return array
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
     * @return array
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
     * @return array
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
     * @return array
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
     * @return array
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
     * @return array
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
     * @return array
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
     * @return array
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
     * @return array
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
     * @return array
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
