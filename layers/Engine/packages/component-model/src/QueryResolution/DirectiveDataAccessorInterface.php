<?php

declare(strict_types=1);

namespace PoP\ComponentModel\QueryResolution;

use PoP\GraphQLParser\Exception\AbstractValueResolutionPromiseException;

interface DirectiveDataAccessorInterface extends FieldOrDirectiveDataAccessorInterface
{
    /**
     * @return array<string,mixed>
     * @throws AbstractValueResolutionPromiseException
     */
    public function getDirectiveArgs(): array;

    /**
     * When the Args contain a "Resolved on Object" Promise,
     * then caching the results will not work across objects,
     * and the cache must then be explicitly cleared.
     */
    public function resetDirectiveArgs(): void;
}
