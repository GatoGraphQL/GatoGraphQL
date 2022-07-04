<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

interface FieldArgumentFieldDataProviderInterface extends FieldDataProviderInterface
{
    public function getField(): FieldInterface;
}
