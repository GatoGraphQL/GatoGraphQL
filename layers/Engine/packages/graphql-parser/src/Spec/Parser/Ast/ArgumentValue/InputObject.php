<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue;

use PoP\GraphQLParser\Exception\Parser\InvalidDynamicContextException;
use PoP\GraphQLParser\Spec\Parser\Ast\AbstractAst;
use PoP\GraphQLParser\Spec\Parser\Ast\WithAstValueInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\WithValueInterface;
use PoP\GraphQLParser\Spec\Parser\Location;
use stdClass;

class InputObject extends AbstractAst implements CoercibleArgumentValueAstInterface, WithAstValueInterface
{
    protected ?stdClass $cachedValue = null;

    /**
     * @param stdClass $object Elements inside can be WithValueInterface or native types (array, int, string, etc)
     */
    public function __construct(
        protected stdClass $object,
        Location $location,
    ) {
        parent::__construct($location);
    }

    protected function doAsQueryString(): string
    {
        return $this->getGraphQLQueryStringFormatter()->getObjectAsQueryString($this->object);
    }

    protected function doAsASTNodeString(): string
    {
        return $this->getGraphQLQueryStringFormatter()->getObjectAsQueryString($this->object);
    }

    /**
     * Transform from Ast to actual value.
     * Eg: replace VariableReferences with their value,
     * nested InputObjects with stdClass, etc
     *
     * @return stdClass
     * @throws InvalidDynamicContextException When accessing non-declared Dynamic Variables
     */
    final public function getValue(): mixed
    {
        if ($this->cachedValue === null) {
            $this->cachedValue = $this->doGetValue();
        }
        return $this->cachedValue;
    }

    /**
     * @throws InvalidDynamicContextException When accessing non-declared Dynamic Variables
     */
    protected function doGetValue(): stdClass
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
     * @return stdClass
     */
    public function getAstValue(): mixed
    {
        return $this->object;
    }

    public function resetCachedValue(): void
    {
        $this->cachedValue = null;
    }
}
