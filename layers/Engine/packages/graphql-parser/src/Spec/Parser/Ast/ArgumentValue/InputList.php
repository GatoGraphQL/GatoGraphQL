<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue;

use PoP\GraphQLParser\Spec\Parser\Ast\AbstractAst;
use PoP\GraphQLParser\Spec\Parser\Ast\WithAstValueInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\WithValueInterface;
use PoP\GraphQLParser\Spec\Parser\Location;

class InputList extends AbstractAst implements ArgumentValueAstInterface, WithAstValueInterface
{
    /**
     * @param mixed[] $list Elements inside can be WithValueInterface or native types (array, int, string, etc)
     */
    public function __construct(
        protected array $list,
        Location $location,
    ) {
        parent::__construct($location);
    }

    protected function doAsQueryString(): string
    {
        return $this->getGraphQLQueryStringFormatter()->getListAsQueryString($this->list);
    }

    protected function doAsASTNodeString(): string
    {
        return $this->getGraphQLQueryStringFormatter()->getListAsQueryString($this->list);
    }

    /**
     * Transform from Ast to actual value.
     * Eg: replace VariableReferences with their value,
     * nested InputObjects with stdClass, etc
     *
     * @return mixed[]
     */
    final public function getValue(): mixed
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
     * @return mixed[]
     */
    public function getAstValue(): mixed
    {
        return $this->list;
    }

    /**
     * Indicate if a field equals another one based on its properties,
     * not on its object hash ID.
     */
    public function equalsTo(InputList $inputList): bool
    {
        return $this->getAstValue() === $inputList->getAstValue();
    }
}
