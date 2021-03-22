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
        foreach ($dbWarnings as $resultItemID => $resultItemWarnings) {
            $this->dbWarnings[$resultItemID] = array_merge(
                $this->dbWarnings[$resultItemID] ?? [],
                $resultItemWarnings
            );
        }
    }
    public function addDBDeprecations(array $dbDeprecations)
    {
        foreach ($dbDeprecations as $resultItemID => $resultItemDeprecations) {
            $this->dbDeprecations[$resultItemID] = array_merge(
                $this->dbDeprecations[$resultItemID] ?? [],
                $resultItemDeprecations
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
    public function retrieveAndClearResultItemDBWarnings(mixed $resultItemID): ?array
    {
        $resultItemDBWarnings = $this->dbWarnings[$resultItemID] ?? null;
        unset($this->dbWarnings[$resultItemID]);
        return $resultItemDBWarnings;
    }
    public function retrieveAndClearResultItemDBDeprecations(mixed $resultItemID): ?array
    {
        $resultItemDBDeprecations = $this->dbDeprecations[$resultItemID] ?? null;
        unset($this->dbDeprecations[$resultItemID]);
        return $resultItemDBDeprecations;
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
