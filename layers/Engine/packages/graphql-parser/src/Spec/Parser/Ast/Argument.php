<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Enum;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputObject;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\VariableReference;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\Exception\ShouldNotHappenException;

class Argument extends AbstractAst
{
    public function __construct(
        protected readonly string $name,
        protected WithValueInterface $value,
        Location $location,
    ) {
        parent::__construct($location);
    }

    protected function doAsQueryString(): string
    {
        return sprintf(
            '%s: %s',
            $this->name,
            $this->value->asQueryString()
        );
    }

    protected function doAsASTNodeString(): string
    {
        return sprintf(
            '(%s: %s)',
            $this->name,
            $this->value->asQueryString()
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValueAST(): WithValueInterface
    {
        return $this->value;
    }

    final public function getValue(): mixed
    {
        return $this->value->getValue();
    }

    /**
     * Indicate if a field equals another one based on its properties,
     * not on its object hash ID.
     */
    public function equalsTo(Argument $argument): bool
    {
        if ($this->getName() !== $argument->getName()) {
            return false;
        }

        $thisValueAST = $this->getValueAST();
        if (get_class($thisValueAST) !== get_class($argument->getValueAST())) {
            return false;
        }

        /**
         * Call ->equalsTo depending on the type of object
         */
        if ($thisValueAST instanceof InputList) {
            /** @var InputList */
            $inputList = $argument->getValueAST();
            return $thisValueAST->equalsTo($inputList);
        }
        if ($thisValueAST instanceof InputObject) {
            /** @var InputObject */
            $inputObject = $argument->getValueAST();
            return $thisValueAST->equalsTo($inputObject);
        }
        if ($thisValueAST instanceof Enum) {
            /** @var Enum */
            $enum = $argument->getValueAST();
            return $thisValueAST->equalsTo($enum);
        }
        if ($thisValueAST instanceof Literal) {
            /** @var Literal */
            $literal = $argument->getValueAST();
            return $thisValueAST->equalsTo($literal);
        }
        if ($thisValueAST instanceof VariableReference) {
            /** @var VariableReference */
            $variableReference = $argument->getValueAST();
            return $thisValueAST->equalsTo($variableReference);
        }
        
        throw new ShouldNotHappenException(
            sprintf(
                $this->__('Cannot recognize the type of the object, of class \'%s\'', 'graphql-parser'),
                get_class($thisValueAST)
            )
        );
    }
}
