<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\Feedback\Tokens;

class FeedbackMessageStore extends \PoP\FieldQuery\FeedbackMessageStore implements FeedbackMessageStoreInterface
{
    /**
     * @var array[]
     */
    protected array $schemaWarnings = [];
    /**
     * @var array<string, array>
     */
    protected array $schemaErrors = [];
    /**
     * @var array<string, array>
     */
    protected array $dbWarnings = [];
    /**
     * @var array<string, array>
     */
    protected array $dbDeprecations = [];
    /**
     * @var string[]
     */
    protected array $logEntries = [];

    public function addDBWarnings(array $dbWarnings)
    {
        foreach ($dbWarnings as $objectID => $objectWarnings) {
            $this->dbWarnings[$objectID] = array_merge(
                $this->dbWarnings[$objectID] ?? [],
                $objectWarnings
            );
        }
    }
    public function addDBDeprecations(array $dbDeprecations)
    {
        foreach ($dbDeprecations as $objectID => $objectDeprecations) {
            $this->dbDeprecations[$objectID] = array_merge(
                $this->dbDeprecations[$objectID] ?? [],
                $objectDeprecations
            );
        }
    }
    public function addSchemaWarnings(array $schemaWarnings)
    {
        $this->schemaWarnings = array_merge(
            $this->schemaWarnings,
            $schemaWarnings
        );
    }
    public function retrieveAndClearObjectDBWarnings(string | int $objectID): ?array
    {
        $objectDBWarnings = $this->dbWarnings[$objectID] ?? null;
        unset($this->dbWarnings[$objectID]);
        return $objectDBWarnings;
    }
    public function retrieveAndClearObjectDBDeprecations(string | int $objectID): ?array
    {
        $objectDBDeprecations = $this->dbDeprecations[$objectID] ?? null;
        unset($this->dbDeprecations[$objectID]);
        return $objectDBDeprecations;
    }

    public function addSchemaError(string $dbKey, string $field, string $error)
    {
        $this->schemaErrors[$dbKey][] = [
            Tokens::PATH => [$field],
            Tokens::MESSAGE => $error,
        ];
    }
    public function retrieveAndClearSchemaErrors(): array
    {
        $schemaErrors = $this->schemaErrors;
        $this->schemaErrors = [];
        return $schemaErrors;
    }
    public function retrieveAndClearSchemaWarnings(): array
    {
        $schemaWarnings = $this->schemaWarnings;
        $this->schemaWarnings = [];
        return $schemaWarnings;
    }
    public function getSchemaErrorsForField(string $dbKey, string $field): ?array
    {
        return $this->schemaErrors[$dbKey][$field] ?? null;
    }

    public function addLogEntry(string $entry): void
    {
        $this->logEntries[] = $entry;
    }

    public function maybeAddLogEntry(string $entry): void
    {
        if (!in_array($entry, $this->logEntries)) {
            $this->addLogEntry($entry);
        }
    }

    public function getLogEntries(): array
    {
        return $this->logEntries;
    }
}
