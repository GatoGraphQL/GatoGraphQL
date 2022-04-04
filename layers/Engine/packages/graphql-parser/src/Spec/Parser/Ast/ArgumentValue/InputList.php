<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue;

use PoP\GraphQLParser\Spec\Parser\Ast\AbstractAst;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\WithAstValueInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\WithValueInterface;
use PoP\GraphQLParser\Spec\Parser\Location;

class InputList extends AbstractAst implements ArgumentValueAstInterface, WithAstValueInterface
{
    protected InputList|InputObject|Argument $parent;

    /**
     * @param mixed[] $list
     */
    public function __construct(
        protected readonly array $list,
        Location $location,
    ) {
        parent::__construct($location);
    }

    public function asQueryString(): string
    {
        return $this->getGraphQLQueryStringFormatter()->getListAsQueryString($this->list);
    }

    public function setParent(InputList|InputObject|Argument $parent): void
    {
        $this->parent = $parent;
    }

    public function getParent(): InputList|InputObject|Argument
    {
        return $this->parent;
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
     * @return mixed[]
     */
    public function getAstValue(): mixed
    {
        return $this->list;
    }
}
