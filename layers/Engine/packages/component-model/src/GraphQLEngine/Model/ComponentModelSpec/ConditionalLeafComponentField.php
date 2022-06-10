<?php

declare(strict_types=1);

namespace PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec;

use PoP\ComponentModel\Component\Component;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class ConditionalLeafComponentField extends AbstractComponentField
{
    /**
     * The condition must be satisfied on the implicit field.
     * When the value of the field is `true`, load the conditional
     * extra modules under the current dataloading position.
     *
     * @param Component[] $conditionalNestedComponents
     */
    public function __construct(
        FieldInterface $field,
        protected array $conditionalNestedComponents,
    ) {
        parent::__construct(
            $field,
        );
    }

    /**
     * @return Component[]
     */
    public function getConditionalNestedComponents(): array
    {
        return $this->conditionalNestedComponents;
    }
}
