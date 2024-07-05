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
     * Convert associative arrays (and their elements) to stdClass,
     * which is the data structure used for inputs in GraphQL.
     *
     * @param array<string,mixed> $variables
     * @return array<string,mixed>
     */
    public function recursivelyConvertVariableEntriesFromArrayToObject(array $variables): array;
}
