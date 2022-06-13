<?php

declare(strict_types=1);

namespace PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

abstract class AbstractComponentFieldNode implements ComponentFieldNodeInterface
{
    public function __construct(
        protected FieldInterface $field,
    ) {
    }

    /**
     * Allow doing `array_unique` based on the underlying Field
     */
    public function __toString(): string
    {
        return $this->field->getUniqueID();
    }

    public function getField(): FieldInterface
    {
        return $this->field;
    }
}
