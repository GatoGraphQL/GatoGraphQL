<?php

declare(strict_types=1);

namespace PoPAPI\API\PersistedQueries;

interface PersistedFragmentManagerInterface
{
    /**
     * @return string[]
     */
    public function getPersistedFragments(): array;
    /**
     * @return string[]
     */
    public function getPersistedFragmentsForSchema(): array;
    public function addPersistedFragment(string $fragmentName, string $fragmentResolution, ?string $description = null): void;
}
