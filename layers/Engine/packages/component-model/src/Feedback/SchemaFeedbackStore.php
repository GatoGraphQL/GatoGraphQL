<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

class SchemaFeedbackStore
{
    /** @var SchemaFeedbackInterface[] */
    private array $schemaErrors = [];
    /** @var SchemaFeedbackInterface[] */
    private array $schemaWarnings = [];
    /** @var SchemaFeedbackInterface[] */
    private array $schemaDeprecations = [];
    /** @var SchemaFeedbackInterface[] */
    private array $schemaNotices = [];
    /** @var SchemaFeedbackInterface[] */
    private array $schemaLogs = [];
    /** @var SchemaFeedbackInterface[] */
    private array $schemaTraces = [];

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
}
