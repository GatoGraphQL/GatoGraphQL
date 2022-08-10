<?php

declare(strict_types=1);

namespace PoP\ComponentModel\QueryResolution;

use PoP\GraphQLParser\Exception\AbstractValueResolutionPromiseException;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

interface FieldDataAccessorInterface extends FieldOrDirectiveDataAccessorInterface
{
    public function getField(): FieldInterface;
    public function getFieldName(): string;
    /**
     * @return array<string,mixed>
     * @throws AbstractValueResolutionPromiseException
     */
    public function getFieldArgs(): array;
}
