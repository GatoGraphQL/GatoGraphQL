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
}
