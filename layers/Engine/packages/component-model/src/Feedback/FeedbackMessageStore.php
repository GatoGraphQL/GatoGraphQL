<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

class FeedbackMessageStore
{
    private array $queryErrors;
    private array $schemaErrors;
    private array $objectErrors;
    private array $queryWarnings;
    private array $schemaWarnings;
    private array $objectWarnings;
    private array $queryDeprecations;
    private array $schemaDeprecations;
    private array $objectDeprecations;
    private array $queryNotices;
    private array $schemaNotices;
    private array $objectNotices;
    private array $queryLogs;
    private array $schemaLogs;
    private array $objectLogs;
    private array $queryTraces;
    private array $schemaTraces;
    private array $objectTraces;

    public function __construct()
    {
        $this->regenerate();
    }

    public function regenerate(): void
    {
        $this->queryErrors = [];
        $this->schemaErrors = [];
        $this->objectErrors = [];
        $this->queryWarnings = [];
        $this->schemaWarnings = [];
        $this->objectWarnings = [];
        $this->queryDeprecations = [];
        $this->schemaDeprecations = [];
        $this->objectDeprecations = [];
        $this->queryNotices = [];
        $this->schemaNotices = [];
        $this->objectNotices = [];
        $this->queryLogs = [];
        $this->schemaLogs = [];
        $this->objectLogs = [];
        $this->queryTraces = [];
        $this->schemaTraces = [];
        $this->objectTraces = [];
    }

    /**
     * @return array
     */
    public function getQueryErrors(): array
    {
        return $this->queryErrors;
    }

    /**
     * @param array $queryErrors
     */
    public function setQueryErrors(array $queryErrors): void
    {
        $this->queryErrors = $queryErrors;
    }

    /**
     * @return array
     */
    public function getSchemaErrors(): array
    {
        return $this->schemaErrors;
    }

    /**
     * @param array $schemaErrors
     */
    public function setSchemaErrors(array $schemaErrors): void
    {
        $this->schemaErrors = $schemaErrors;
    }

    /**
     * @return array
     */
    public function getObjectErrors(): array
    {
        return $this->objectErrors;
    }

    /**
     * @param array $objectErrors
     */
    public function setObjectErrors(array $objectErrors): void
    {
        $this->objectErrors = $objectErrors;
    }

    /**
     * @return array
     */
    public function getQueryWarnings(): array
    {
        return $this->queryWarnings;
    }

    /**
     * @param array $queryWarnings
     */
    public function setQueryWarnings(array $queryWarnings): void
    {
        $this->queryWarnings = $queryWarnings;
    }

    /**
     * @return array
     */
    public function getSchemaWarnings(): array
    {
        return $this->schemaWarnings;
    }

    /**
     * @param array $schemaWarnings
     */
    public function setSchemaWarnings(array $schemaWarnings): void
    {
        $this->schemaWarnings = $schemaWarnings;
    }

    /**
     * @return array
     */
    public function getObjectWarnings(): array
    {
        return $this->objectWarnings;
    }

    /**
     * @param array $objectWarnings
     */
    public function setObjectWarnings(array $objectWarnings): void
    {
        $this->objectWarnings = $objectWarnings;
    }

    /**
     * @return array
     */
    public function getQueryDeprecations(): array
    {
        return $this->queryDeprecations;
    }

    /**
     * @param array $queryDeprecations
     */
    public function setQueryDeprecations(array $queryDeprecations): void
    {
        $this->queryDeprecations = $queryDeprecations;
    }

    /**
     * @return array
     */
    public function getSchemaDeprecations(): array
    {
        return $this->schemaDeprecations;
    }

    /**
     * @param array $schemaDeprecations
     */
    public function setSchemaDeprecations(array $schemaDeprecations): void
    {
        $this->schemaDeprecations = $schemaDeprecations;
    }

    /**
     * @return array
     */
    public function getObjectDeprecations(): array
    {
        return $this->objectDeprecations;
    }

    /**
     * @param array $objectDeprecations
     */
    public function setObjectDeprecations(array $objectDeprecations): void
    {
        $this->objectDeprecations = $objectDeprecations;
    }

    /**
     * @return array
     */
    public function getQueryNotices(): array
    {
        return $this->queryNotices;
    }

    /**
     * @param array $queryNotices
     */
    public function setQueryNotices(array $queryNotices): void
    {
        $this->queryNotices = $queryNotices;
    }

    /**
     * @return array
     */
    public function getSchemaNotices(): array
    {
        return $this->schemaNotices;
    }

    /**
     * @param array $schemaNotices
     */
    public function setSchemaNotices(array $schemaNotices): void
    {
        $this->schemaNotices = $schemaNotices;
    }

    /**
     * @return array
     */
    public function getObjectNotices(): array
    {
        return $this->objectNotices;
    }

    /**
     * @param array $objectNotices
     */
    public function setObjectNotices(array $objectNotices): void
    {
        $this->objectNotices = $objectNotices;
    }

    /**
     * @return array
     */
    public function getQueryLogs(): array
    {
        return $this->queryLogs;
    }

    /**
     * @param array $queryLogs
     */
    public function setQueryLogs(array $queryLogs): void
    {
        $this->queryLogs = $queryLogs;
    }

    /**
     * @return array
     */
    public function getSchemaLogs(): array
    {
        return $this->schemaLogs;
    }

    /**
     * @param array $schemaLogs
     */
    public function setSchemaLogs(array $schemaLogs): void
    {
        $this->schemaLogs = $schemaLogs;
    }

    /**
     * @return array
     */
    public function getObjectLogs(): array
    {
        return $this->objectLogs;
    }

    /**
     * @param array $objectLogs
     */
    public function setObjectLogs(array $objectLogs): void
    {
        $this->objectLogs = $objectLogs;
    }

    /**
     * @return array
     */
    public function getQueryTraces(): array
    {
        return $this->queryTraces;
    }

    /**
     * @param array $queryTraces
     */
    public function setQueryTraces(array $queryTraces): void
    {
        $this->queryTraces = $queryTraces;
    }

    /**
     * @return array
     */
    public function getSchemaTraces(): array
    {
        return $this->schemaTraces;
    }

    /**
     * @param array $schemaTraces
     */
    public function setSchemaTraces(array $schemaTraces): void
    {
        $this->schemaTraces = $schemaTraces;
    }

    /**
     * @return array
     */
    public function getObjectTraces(): array
    {
        return $this->objectTraces;
    }

    /**
     * @param array $objectTraces
     */
    public function setObjectTraces(array $objectTraces): void
    {
        $this->objectTraces = $objectTraces;
    }
}
