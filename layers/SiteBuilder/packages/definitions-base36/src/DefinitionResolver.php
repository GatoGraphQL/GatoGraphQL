<?php

declare(strict_types=1);

namespace PoP\Base36Definitions;

use PoP\Definitions\DefinitionResolverInterface;

class DefinitionResolver implements DefinitionResolverInterface
{
    /**
     * @var array<string,int>
     */
    private array $definition_counters = [];

    /**
     * Allow Persistent Definitions to set a different value
     *
     * @param array<string,mixed> $persisted_data
     */
    public function setPersistedData(array $persisted_data): void
    {
        // The first time there will be no persisted data
        if ($counters = $persisted_data['counters']) {
            $this->definition_counters = $counters;
        }
    }
    /**
     * @return array<string,mixed>
     */
    public function getDataToPersist(): array
    {
        return array(
            'counters' => $this->definition_counters,
        );
    }

    public function getDefinition(string $name, string $group): string
    {
        // Counter: cannot start with a number, or the id will get confused
        // First number is 10, that is "a" in base 36
        if (!$this->definition_counters[$group]) {
            $this->definition_counters[$group] = 10;
        }

        // Convert the number to base 36 to save chars
        $counter = base_convert((string) $this->definition_counters[$group], 10, 36);

        // Increase the counter by 1.
        $this->definition_counters[$group] = $this->definition_counters[$group] + 1;

        // If we reach a number whose base 36 conversion starts with a number, and not a letter, then skip
        if ($this->definition_counters[$group] == 36) {
            // 36 in base 10 = 10 in base 36
            // 360 in base 10 = a0 in base 36
            $this->definition_counters[$group] = 360;
        } elseif ($this->definition_counters[$group] == 1296) {
            // 1296 in base 10 = 100 in base 36
            // 12960 in base 10 = a00 in base 36
            $this->definition_counters[$group] = 12960;
        } elseif ($this->definition_counters[$group] == 46656) {
            // 46656 in base 10 = 1000 in base 36
            // 466560 in base 10 = a000 in base 36
            $this->definition_counters[$group] = 466560;
        }

        // That's it, return the result
        return $counter;
    }
}
