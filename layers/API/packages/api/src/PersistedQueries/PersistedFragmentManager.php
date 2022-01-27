<?php

declare(strict_types=1);

namespace PoPAPI\API\PersistedQueries;

use PoPAPI\API\Schema\SchemaDefinition;

class PersistedFragmentManager implements PersistedFragmentManagerInterface
{
    /**
     * @var array<string, string>
     */
    protected array $persistedFragments = [];
    /**
     * @var array<string, array>
     */
    protected array $persistedFragmentsForSchema = [];

    public function getPersistedFragments(): array
    {
        return $this->persistedFragments;
    }

    public function getPersistedFragmentsForSchema(): array
    {
        return $this->persistedFragmentsForSchema;
    }

    public function addPersistedFragment(string $fragmentName, string $fragmentResolution, ?string $description = null): void
    {
        $this->persistedFragments[$fragmentName] = $fragmentResolution;
        $this->persistedFragmentsForSchema[$fragmentName] = [
            SchemaDefinition::NAME => $fragmentName,
        ];
        if ($description) {
            $this->persistedFragmentsForSchema[$fragmentName][SchemaDefinition::DESCRIPTION] = $description;
        }
        $this->persistedFragmentsForSchema[$fragmentName][SchemaDefinition::FRAGMENT_RESOLUTION] = $fragmentResolution;
    }
}
