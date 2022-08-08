<?php

declare(strict_types=1);

namespace PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

interface ComponentFieldNodeInterface
{
    public function getField(): FieldInterface;
    public function sortAgainst(ComponentFieldNodeInterface $againstComponentFieldNode): int;
}
