<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\Services;

use PoPSchema\SchemaCommons\Enums\Behaviors;

interface AllowOrDenySettingsServiceInterface
{
    /**
     * Check if the allow/denylist validation fails
     * Compare for full match or regex
     *
     * @param string[] $entries
     */
    public function isEntryAllowed(string $name, array $entries, Behaviors $behavior): bool;
}
