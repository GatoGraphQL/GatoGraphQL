<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue;

use PoP\GraphQLParser\Spec\Parser\Ast\AbstractAst;
use PoP\GraphQLParser\Spec\Parser\Ast\WithAstValueInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\WithValueInterface;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\Exception\ShouldNotHappenException;
use stdClass;

use function get_object_vars;

class InputObject extends AbstractAst implements ArgumentValueAstInterface, WithAstValueInterface
{
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
     */
    final public function getValue(): mixed
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

    /**
     * Indicate if a field equals another one based on its properties,
     * not on its object hash ID.
     */
    public function equalsTo(InputObject $inputObject): bool
    {
        $thisInputObjectValue = $this->getAstValue();
        $againstInputObjectValue = $inputObject->getAstValue();
        $thisInputObjectValueKeys = get_object_vars($thisInputObjectValue);
        $againstInputObjectValueKeys = get_object_vars($againstInputObjectValue);
        $thisInputObjectValueCount = count($thisInputObjectValueKeys);
        if ($thisInputObjectValueCount !== count($againstInputObjectValueKeys)) {
            return false;
        }
        foreach ($thisInputObjectValueKeys as $key) {
            if (!property_exists($againstInputObjectValue, $key)) {
                return false;
            };
            $thisInputObjectElemValue = $thisInputObjectValue->$key;
            $againstInputObjectElemValue = $againstInputObjectValue->$key;

            if (($thisInputObjectElemValue === null && $againstInputObjectElemValue !== null)
                || ($thisInputObjectElemValue !== null && $againstInputObjectElemValue === null)
            ) {
                return false;
            }

            if ((is_object($thisInputObjectElemValue) && !is_object($againstInputObjectElemValue))
                || (!is_object($thisInputObjectElemValue) && is_object($againstInputObjectElemValue))
            ) {
                return false;
            }

            if (is_object($thisInputObjectElemValue)) {
                if (get_class($thisInputObjectElemValue) !== get_class($againstInputObjectElemValue)) {
                    return false;
                }
                
                /**
                 * Call ->equalsTo depending on the type of object
                 */
                if ($thisInputObjectElemValue instanceof InputList) {
                    /** @var InputList */
                    $inputList = $againstInputObjectElemValue->getValueAST();
                    if (!$thisInputObjectElemValue->equalsTo($inputList)) {
                        return false;
                    }
                    continue;
                }
                if ($thisInputObjectElemValue instanceof InputObject) {
                    /** @var InputObject */
                    $againstInputObject = $againstInputObjectElemValue->getValueAST();
                    if (!$thisInputObjectElemValue->equalsTo($againstInputObject)) {
                        return false;
                    }
                    continue;
                }
                if ($thisInputObjectElemValue instanceof Enum) {
                    /** @var Enum */
                    $enum = $againstInputObjectElemValue->getValueAST();
                    if (!$thisInputObjectElemValue->equalsTo($enum)) {
                        return false;
                    }
                    continue;
                }
                if ($thisInputObjectElemValue instanceof Literal) {
                    /** @var Literal */
                    $literal = $againstInputObjectElemValue->getValueAST();
                    if (!$thisInputObjectElemValue->equalsTo($literal)) {
                        return false;
                    }
                    continue;
                }
                if ($thisInputObjectElemValue instanceof VariableReference) {
                    /** @var VariableReference */
                    $variableReference = $againstInputObjectElemValue->getValueAST();
                    if (!$thisInputObjectElemValue->equalsTo($variableReference)) {
                        return false;
                    }
                    continue;
                }
                
                throw new ShouldNotHappenException(
                    sprintf(
                        $this->__('Cannot recognize the type of the object, of class \'%s\'', 'graphql-parser'),
                        get_class($thisInputObjectElemValue)
                    )
                );
            }
            
            /**
             * The element is a native type (bool, string, int, or float)
             */
            if ($thisInputObjectElemValue !== $againstInputObjectElemValue) {
                return false;
            }
        }
        
        return true;
    }
}
