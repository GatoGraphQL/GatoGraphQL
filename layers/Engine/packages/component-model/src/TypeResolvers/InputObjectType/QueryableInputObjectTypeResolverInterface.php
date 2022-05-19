<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\InputObjectType;

use stdClass;

/**
 * Based on GraphQL InputObject Type
 *
 * @see https://spec.graphql.org/draft/#sec-Input-Objects
 */
interface QueryableInputObjectTypeResolverInterface extends InputObjectTypeResolverInterface
{
    /**
     * Have the input field filter the query
     *
     * @return array|null A FilterInput component, or null
     */
    public function getInputFieldFilterInput(string $inputFieldName): ?array;
    public function getConsolidatedInputFieldFilterInput(string $inputFieldName): ?array;
    /**
     * Apply the FilterInputs to produce the filtering query
     *
     * @param array<string, mixed> $query
     * @param stdClass|stdClass[]|array<stdClass[]> $inputValue
     */
    public function integrateInputValueToFilteringQueryArgs(array &$query, stdClass|array $inputValue): void;
}
