<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue;

use PoP\GraphQLParser\Spec\Parser\Ast\AbstractAst;
use PoP\GraphQLParser\Spec\Parser\Ast\WithAstValueInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\WithValueInterface;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\Exception\ShouldNotHappenException;

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
        $thisInputListValue = $this->getAstValue();
        $againstInputListValue = $inputList->getAstValue();
        $thisInputListValueCount = count($thisInputListValue);
        if ($thisInputListValueCount !== count($againstInputListValue)) {
            return false;
        }
        for ($i = 0; $i < $thisInputListValueCount; $i++) {
            $thisInputListElemValue = $thisInputListValue[$i];
            $againstInputListElemValue = $againstInputListValue[$i];

            if (($thisInputListElemValue === null && $againstInputListElemValue !== null)
                || ($thisInputListElemValue !== null && $againstInputListElemValue === null)
            ) {
                return false;
            }

            if ((is_object($thisInputListElemValue) && !is_object($againstInputListElemValue))
                || (!is_object($thisInputListElemValue) && is_object($againstInputListElemValue))
            ) {
                return false;
            }

            if (is_object($thisInputListElemValue)) {            
                if (get_class($thisInputListElemValue) !== get_class($againstInputListElemValue)) {
                    return false;
                }                
                /**
                 * Call ->equalsTo depending on the type of object
                 */
                if ($thisInputListElemValue instanceof InputList) {
                    /** @var InputList */
                    $againstInputList = $againstInputListElemValue;
                    if (!$thisInputListElemValue->equalsTo($againstInputList)) {
                        return false;
                    }
                    continue;
                }
                if ($thisInputListElemValue instanceof InputObject) {
                    /** @var InputObject */
                    $inputObject = $againstInputListElemValue;
                    if (!$thisInputListElemValue->equalsTo($inputObject)) {
                        return false;
                    }
                    continue;
                }
                if ($thisInputListElemValue instanceof Enum) {
                    /** @var Enum */
                    $enum = $againstInputListElemValue;
                    if (!$thisInputListElemValue->equalsTo($enum)) {
                        return false;
                    }
                    continue;
                }
                if ($thisInputListElemValue instanceof Literal) {
                    /** @var Literal */
                    $literal = $againstInputListElemValue;
                    if (!$thisInputListElemValue->equalsTo($literal)) {
                        return false;
                    }
                    continue;
                }
                if ($thisInputListElemValue instanceof VariableReferenceInterface) {
                    /** @var VariableReferenceInterface */
                    $variableReference = $againstInputListElemValue;
                    if (!$thisInputListElemValue->equalsTo($variableReference)) {
                        return false;
                    }
                    continue;
                }
            
                throw new ShouldNotHappenException(
                    sprintf(
                        $this->__('Cannot recognize the type of the object, of class \'%s\'', 'graphql-parser'),
                        get_class($thisInputListElemValue)
                    )
                );
            }
            
            /**
             * The element is a native type (bool, string, int, or float)
             */
            if ($thisInputListElemValue !== $againstInputListElemValue) {
                return false;
            }
        }

        return true;
    }
}
