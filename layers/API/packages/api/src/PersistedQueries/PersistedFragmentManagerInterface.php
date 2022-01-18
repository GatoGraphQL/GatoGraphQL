<?php

declare(strict_types=1);

namespace PoPAPI\API\PersistedQueries;

interface PersistedFragmentManagerInterface
{
    public function getPersistedFragments(): array;
    public function getPersistedFragmentsForSchema(): array;
    public function addPersistedFragment(string $fragmentName, string $fragmentResolution, ?string $description = null): void;
}
