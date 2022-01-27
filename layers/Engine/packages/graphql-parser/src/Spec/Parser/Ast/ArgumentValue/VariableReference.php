<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue;

use LogicException;
use PoP\GraphQLParser\Spec\Parser\Ast\AbstractAst;
use PoP\GraphQLParser\Spec\Parser\Ast\WithValueInterface;
use PoP\GraphQLParser\Spec\Parser\Location;

class VariableReference extends AbstractAst implements WithValueInterface
{
    public function __construct(
        private string $name,
        private ?Variable $variable,
        Location $location,
    ) {
        parent::__construct($location);
    }

    public function getVariable(): ?Variable
    {
        return $this->variable;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the value from the context or from the variable
     *
     * @throws LogicException
     */
    public function getValue(): mixed
    {
        if ($this->variable === null) {
            throw new LogicException($this->getVariableDoesNotExistErrorMessage($this->name));
        }

        return $this->variable->getValue();
    }

    protected function getVariableDoesNotExistErrorMessage(string $variableReferenceName): string
    {
        return sprintf('No variable exists for variable reference \'%s\'', $variableReferenceName);
    }
}
