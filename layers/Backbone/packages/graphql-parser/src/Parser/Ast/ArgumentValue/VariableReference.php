<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue;

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
     */
    public function getValue(): mixed
    {
        $variableValues = $this->context->getVariableValues();
        return $variableValues[$this->name] ?? $this->variable?->getDefaultValue() ?? null;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
