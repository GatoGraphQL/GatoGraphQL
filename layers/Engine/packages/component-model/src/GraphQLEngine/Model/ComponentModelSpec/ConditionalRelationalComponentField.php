<?php

declare(strict_types=1);

namespace PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class ConditionalRelationalComponentField extends AbstractComponentField
{
    /**
     * The condition must be satisfied on the implicit field.
     * When the value of the field is `true`, load the conditional
     * domain switching fields.
     *
     * @param RelationalComponentField[] $relationalComponentFields
     */
    public function __construct(
        FieldInterface $field,
        protected array $relationalComponentFields,
    ) {
        parent::__construct(
            $field,
        );
    }

    /**
     * @return RelationalComponentField[]
     */
    public function getRelationalComponentFields(): array
    {
        return $this->relationalComponentFields;
    }
}
