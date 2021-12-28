<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue;

use PoPBackbone\GraphQLParser\Parser\Ast\AbstractAst;
use PoPBackbone\GraphQLParser\Parser\Ast\WithAstValueInterface;
use PoPBackbone\GraphQLParser\Parser\Ast\WithValueInterface;
use PoPBackbone\GraphQLParser\Parser\Location;
use stdClass;

class InputObject extends AbstractAst implements WithValueInterface, WithAstValueInterface
{
    public function __construct(
        protected stdClass $object,
        Location $location,
    ) {
        parent::__construct($location);
    }

    /**
     * Transform from Ast to actual value.
     * Eg: replace VariableReferences with their value,
     * nested InputObjects with stdClass, etc
     *
     * @return stdClass
     */
    public function getValue(): mixed
    {
        $object = new stdClass();
        foreach ((array) $this->object as $key => $value) {
            if ($value instanceof WithValueInterface) {
                $object->$key = $value->getValue();
                continue;
            }
            $object->$key = $value;
        }
        return $object;
    }

    /**
     * @param stdClass $value
     */
    public function setValue(mixed $value): void
    {
        $this->object = $value;
    }

    /**
     * @return stdClass
     */
    public function getAstValue(): mixed
    {
        return $this->object;
    }
}
