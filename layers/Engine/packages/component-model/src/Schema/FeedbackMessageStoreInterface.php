<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

interface FeedbackMessageStoreInterface extends \PoP\FieldQuery\FeedbackMessageStoreInterface
{
    public function addObjectWarnings(array $objectWarnings);
    public function addDBDeprecations(array $objectDeprecations);
    public function addSchemaWarnings(array $schemaWarnings);
    public function retrieveAndClearObjectWarnings(string | int $objectID): ?array;
    public function retrieveAndClearObjectDeprecations(string | int $objectID): ?array;
    public function addSchemaError(string $dbKey, string $field, string $error);
    public function retrieveAndClearSchemaErrors(): array;
    public function retrieveAndClearSchemaWarnings(): array;
    public function getSchemaErrorsForField(string $dbKey, string $field): ?array;
    public function addLogEntry(string $entry): void;
    public function maybeAddLogEntry(string $entry): void;
    public function getLogEntries(): array;
}
