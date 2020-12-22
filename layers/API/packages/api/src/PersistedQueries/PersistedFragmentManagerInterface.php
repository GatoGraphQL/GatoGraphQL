<?php

declare(strict_types=1);

namespace PoP\API\PersistedQueries;

interface PersistedFragmentManagerInterface
{
    public function getPersistedFragments(): array;
    public function getPersistedFragmentsForSchema(): array;
    public function add(string $fragmentName, string $fragmentResolution, ?string $description = null): void;
}
