<?php

declare(strict_types=1);

namespace PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

interface ComponentFieldInterface extends FieldInterface
{
    public function getField(): FieldInterface;
}
