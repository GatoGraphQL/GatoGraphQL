<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue;

use PoPBackbone\GraphQLParser\Parser\Ast\AbstractAst;
use PoPBackbone\GraphQLParser\Parser\Ast\WithValueInterface;
use PoPBackbone\GraphQLParser\Parser\Location;

class InputList extends AbstractAst implements WithValueInterface
{
    /**
     * @param mixed[] $list
     */
    public function __construct(
        protected array $list,
        Location $location,
    ) {
        parent::__construct($location);
    }

    /**
     * Transform from Ast to actual value.
     * Eg: replace VariableReferences with their value,
     * nested InputObjects with stdClass, etc
     *
     * @return mixed[]
     */
    public function getValue(): mixed
    {
        $list = [];
        foreach ($this->list as $key => $value) {
            if ($value instanceof WithValueInterface) {
                $list[$key] = $value->getValue();
                continue;
            }
            $list[$key] = $value;
        }
        return $list;
    }

    /**
     * @param mixed[] $value
     */
    public function setValue(mixed $value): void
    {
        $this->list = $value;
    }
}
