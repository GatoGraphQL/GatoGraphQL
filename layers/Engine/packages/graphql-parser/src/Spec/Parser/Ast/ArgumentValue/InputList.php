<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue;

use PoP\GraphQLParser\Spec\Parser\Ast\AbstractAst;
use PoP\GraphQLParser\Spec\Parser\Ast\WithAstValueInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\WithValueInterface;
use PoP\GraphQLParser\Spec\Parser\Location;

class InputList extends AbstractAst implements WithValueInterface, WithAstValueInterface
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

    public function asQueryString(): string
    {
        return $this->getListAsQueryString($this->list);
    }

    /**
     * @param mixed[] $list
     */
    protected function getListAsQueryString(array $list): string
    {
        $listStrElems = [];
        foreach ($list as $elem) {
            if (is_array($elem)) {
                $listStrElems[] = $this->getListAsQueryString($elem);
                continue;
            }
            if ($elem === null) {
                $listStrElems[] = 'null';
                continue;
            }
            if (is_bool($elem)) {
                $listStrElems[] = $elem ? 'true' : 'false';
                continue;
            }
            if (is_numeric($elem)) {
                $listStrElems[] = $elem;
                continue;
            }
            // String, wrap between quotes
            $listStrElems[] = sprintf('"%s"', $elem);
        }
        return sprintf(
            '[%s]',
            implode(', ', $listStrElems)
        );
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

    /**
     * @return mixed[]
     */
    public function getAstValue(): mixed
    {
        return $this->list;
    }
}
