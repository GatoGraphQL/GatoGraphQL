<?php

declare(strict_types=1);

namespace PoP\ComponentModel\QueryResolution;

use PoP\GraphQLParser\Exception\AbstractValueResolutionPromiseException;

class DirectiveDataAccessor implements DirectiveDataAccessorInterface
{
    use FieldOrDirectiveDataAccessorTrait;

    /**
     * A ObjectResolvedFieldValueReference will return a ValueResolutionPromiseInterface,
     * which must be resolved to the actual value after its corresponding
     * Field was resolved.
     *
     * @var array<string,mixed>
     */
    protected ?array $resolvedDirectiveArgs = null;

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
        return $this->getResolvedDirectiveArgs();
    }

    /**
     * @return array<string,mixed>
     * @throws AbstractValueResolutionPromiseException
     */
    protected function getResolvedDirectiveArgs(): array
    {
        if ($this->resolvedDirectiveArgs === null) {
            $this->resolvedDirectiveArgs = $this->doGetResolvedFieldOrDirectiveArgs($this->unresolvedDirectiveArgs);
        }
        return $this->resolvedDirectiveArgs;
    }

    /**
     * @return array<string,mixed>
     * @throws AbstractValueResolutionPromiseException
     */
    protected function getResolvedFieldOrDirectiveArgs(): array
    {
        return $this->getResolvedDirectiveArgs();
    }
}
