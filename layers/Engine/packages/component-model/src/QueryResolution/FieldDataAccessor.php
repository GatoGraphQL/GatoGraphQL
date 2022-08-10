<?php

declare(strict_types=1);

namespace PoP\ComponentModel\QueryResolution;

use PoP\GraphQLParser\Exception\AbstractValueResolutionPromiseException;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class FieldDataAccessor implements FieldDataAccessorInterface
{
    use FieldOrDirectiveDataAccessorTrait;

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
        return $this->getResolvedFieldOrDirectiveArgs();
    }

    /**
     * @return array<string,mixed>
     */
    protected function getUnresolvedFieldOrDirectiveArgs(): array
    {
        return $this->unresolvedFieldArgs;
    }
}
