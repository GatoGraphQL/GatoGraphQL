<?php

declare(strict_types=1);

namespace PoP\ComponentModel\QueryResolution;

use PoP\GraphQLParser\Exception\AbstractValueResolutionPromiseException;

class DirectiveDataAccessor implements DirectiveDataAccessorInterface
{
    use FieldOrDirectiveDataAccessorTrait;

    /**
     * @param array<string,mixed> $unresolvedDirectiveArgs
     */
    public function __construct(
        /** @var array<string,mixed> */
        protected array $unresolvedDirectiveArgs,
    ) {
    }

    /**
     * @return array<string,mixed>
     * @throws AbstractValueResolutionPromiseException
     */
    public function getDirectiveArgs(): array
    {
        return $this->getResolvedFieldOrDirectiveArgs();
    }

    /**
     * @return array<string,mixed>
     */
    protected function getUnresolvedFieldOrDirectiveArgs(): array
    {
        return $this->unresolvedDirectiveArgs;
    }

    /**
     * When the Args contain a "Resolved on Object" Promise,
     * then caching the results will not work across objects,
     * and the cache must then be explicitly cleared.
     */
    public function resetDirectiveArgs(): void
    {
        $this->resetResolvedFieldOrDirectiveArgs();
    }
}
