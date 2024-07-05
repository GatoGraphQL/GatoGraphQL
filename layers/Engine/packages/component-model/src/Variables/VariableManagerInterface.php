<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Variables;

interface VariableManagerInterface
{
    /**
     * @return array<string,mixed>
     */
    public function getVariablesFromRequest(): array;
    /**
     * Convert associative arrays to objects in the
     * variables JSON entries, recursively.
     *
     * stdClass is the data structure used for inputs in GraphQL
     *
     * For instance, storing this JSON:
     *
     *   {
     *     "languageMapping": {
     *       "nb": "no"
     *     }
     *   }
     *
     * ...must be interpreted as object, not array.
     *
     * @param array<string,mixed> $variables
     * @return array<string,mixed>
     */
    public function recursivelyConvertVariableEntriesFromArrayToObject(array $variables): array;
}
