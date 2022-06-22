<?php

declare(strict_types=1);

namespace PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec;

use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;

class LeafComponentFieldNode extends AbstractComponentFieldNode
{
    /**
     * Retrieve a new instance with all the properties from the LeafField
     */
    public static function fromLeafField(
        LeafField $leafField,
    ): self {
        return new self(
            $leafField,
        );
    }
}
