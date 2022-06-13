<?php

declare(strict_types=1);

namespace PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec;

use PoP\ComponentModel\Component\Component;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;

class RelationalComponentFieldNode extends AbstractComponentFieldNode
{
    /**
     * @param Component[] $nestedComponents
     */
    public function __construct(
        FieldInterface $field,
        protected array $nestedComponents,
    ) {
        parent::__construct(
            $field,
        );
    }

    /**
     * Retrieve a new instance with all the properties from the RelationalField
     *
     * @param Component[] $nestedComponents
     */
    public static function fromRelationalField(
        RelationalField $relationalField,
        array $nestedComponents,
    ): self {
        return new self(
            $relationalField,
            $nestedComponents,
        );
    }

    /**
     * @return Component[]
     */
    public function getNestedComponents(): array
    {
        return $this->nestedComponents;
    }
}
