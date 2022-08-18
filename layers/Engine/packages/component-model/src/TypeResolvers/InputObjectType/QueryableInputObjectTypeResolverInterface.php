<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\InputObjectType;

use PoP\ComponentModel\FilterInputs\FilterInputInterface;
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
     */
    public function getInputFieldFilterInput(string $inputFieldName): ?FilterInputInterface;
    public function getConsolidatedInputFieldFilterInput(string $inputFieldName): ?FilterInputInterface;
    /**
     * Apply the FilterInputs to produce the filtering query
     *
     * @param array<string,mixed> $query
     * @param stdClass|stdClass[]|array<stdClass[]> $inputValue
     */
    public function integrateInputValueToFilteringQueryArgs(array &$query, stdClass|array $inputValue): void;
}
