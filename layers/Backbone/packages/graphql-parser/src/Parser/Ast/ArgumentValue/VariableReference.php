<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue;

use LogicException;
use PoPBackbone\GraphQLParser\Execution\Context;
use PoPBackbone\GraphQLParser\Parser\Ast\AbstractAst;
use PoPBackbone\GraphQLParser\Parser\Ast\WithValueInterface;
use PoPBackbone\GraphQLParser\Parser\Location;

class VariableReference extends AbstractAst implements WithValueInterface
{
    private Context $context;

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

    public function setContext(Context $context): void
    {
        $this->context = $context;
    }

    /**
     * Get the value from the context or from the variable
     *
     * @throws LogicException
     */
    public function getValue(): mixed
    {
        if ($this->context->hasVariableValue($this->name)) {
            return $this->context->getVariableValue($this->name);
        }
        if ($this->variable?->hasDefaultValue()) {
            return $this->variable->getDefaultValue();
        }
        throw new LogicException($this->getValueIsNotSetForVariableErrorMessage($this->name));
    }

    protected function getValueIsNotSetForVariableErrorMessage(string $variableName): string
    {
        return sprintf('Value is not set for variable \'%s\'', $variableName);
    }

    public function getName(): string
    {
        return $this->name;
    }
}
