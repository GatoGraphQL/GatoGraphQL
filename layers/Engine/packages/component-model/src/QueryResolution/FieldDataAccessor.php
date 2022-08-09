<?php

declare(strict_types=1);

namespace PoP\ComponentModel\QueryResolution;

use PoP\GraphQLParser\Exception\AbstractValueResolutionPromiseException;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class FieldDataAccessor implements FieldDataAccessorInterface
{
    use FieldOrDirectiveDataAccessorTrait;

    /**
     * A ObjectResolvedFieldValueReference will return a ValueResolutionPromiseInterface,
     * which must be resolved to the actual value after its corresponding
     * Field was resolved.
     *
     * @var array<string,mixed>
     */
    protected ?array $resolvedFieldArgs = null;

    public function __construct(
        protected FieldInterface $field,
        /** @var array<string,mixed> */
        protected array $unresolvedFieldArgs,
    ) {
    }

    public function getField(): FieldInterface
    {
        return $this->field;
    }

    final public function getFieldName(): string
    {
        return $this->field->getName();
    }

    /**
     * @return array<string,mixed>
     * @throws AbstractValueResolutionPromiseException
     */
    public function getFieldArgs(): array
    {
        return $this->getResolvedFieldArgs();
    }

    /**
     * @return array<string,mixed>
     * @throws AbstractValueResolutionPromiseException
     */
    protected function getResolvedFieldArgs(): array
    {
        if ($this->resolvedFieldArgs === null) {
            $this->resolvedFieldArgs = $this->doGetResolvedFieldOrDirectiveArgs($this->unresolvedFieldArgs);
        }
        return $this->resolvedFieldArgs;
    }

    /**
     * @return array<string,mixed>
     * @throws AbstractValueResolutionPromiseException
     */
    protected function getResolvedFieldOrDirectiveArgs(): array
    {
        return $this->getResolvedFieldArgs();
    }
}
