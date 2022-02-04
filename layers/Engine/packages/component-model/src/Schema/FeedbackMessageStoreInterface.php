<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\FieldQuery\FeedbackMessageStoreInterface as UpstreamFeedbackMessageStoreInterface;

interface FeedbackMessageStoreInterface extends UpstreamFeedbackMessageStoreInterface
{
    public function addLogEntry(string $entry): void;
    public function maybeAddLogEntry(string $entry): void;
    public function getLogEntries(): array;
}
