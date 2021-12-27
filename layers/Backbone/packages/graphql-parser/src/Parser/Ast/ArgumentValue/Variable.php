<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue;

use LogicException;
use PoPBackbone\GraphQLParser\Execution\Context;
use PoPBackbone\GraphQLParser\Parser\Ast\AbstractAst;
use PoPBackbone\GraphQLParser\Parser\Ast\WithValueInterface;
use PoPBackbone\GraphQLParser\Parser\Location;
use stdClass;

class Variable extends AbstractAst implements WithValueInterface
{
    private ?Context $context = null;

    private bool $hasDefaultValue = false;

    private InputList|InputObject|Literal|null $defaultValue = null;

    public function __construct(
        private string $name,
        private string $type,
        private bool $isRequired,
        private bool $isArray,
        private bool $isArrayElementRequired,
        Location $location,
    ) {
        parent::__construct($location);
    }

    public function setContext(Context $context): void
    {
        $this->context = $context;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getTypeName(): string
    {
        return $this->type;
    }

    public function setTypeName(string $type): void
    {
        $this->type = $type;
    }

    public function isArray(): bool
    {
        return $this->isArray;
    }

    public function setIsArray(bool $isArray): void
    {
        $this->isArray = $isArray;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function setRequired(bool $isRequired): void
    {
        $this->required = $isRequired;
    }

    public function hasDefaultValue(): bool
    {
        return $this->hasDefaultValue;
    }

    public function getDefaultValue(): InputList|InputObject|Literal|null
    {
        return $this->defaultValue;
    }

    public function setDefaultValue(InputList|InputObject|Literal|null $defaultValue): void
    {
        $this->hasDefaultValue = true;
        $this->defaultValue = $defaultValue;
    }

    public function isArrayElementRequired(): bool
    {
        return $this->isArrayElementRequired;
    }

    public function setArrayElementRequired(bool $isArrayElementRequired): void
    {
        $this->isArrayElementRequired = $isArrayElementRequired;
    }

    /**
     * Get the value from the context or from the variable
     *
     * @return InputList|InputObject|Literal|null
     * @throws LogicException
     */
    public function getValue(): mixed
    {
        if ($this->context === null) {
            throw new LogicException($this->getContextNotSetErrorMessage($this->name));
        }
        if ($this->context->hasVariableValue($this->name)) {
            $variableValue = $this->context->getVariableValue($this->name);
            if (is_array($variableValue)) {
                return new InputList($variableValue, $this->getLocation());    
            }
            if ($variableValue instanceof stdClass) {
                return new InputObject($variableValue, $this->getLocation());    
            }
            return new Literal($variableValue, $this->getLocation());
        }
        if ($this->hasDefaultValue()) {
            return $this->getDefaultValue();
        }
        throw new LogicException($this->getValueIsNotSetForVariableErrorMessage($this->name));
    }

    protected function getContextNotSetErrorMessage(string $variableName): string
    {
        return sprintf('Context has not been set for variable \'%s\'', $variableName);
    }

    protected function getValueIsNotSetForVariableErrorMessage(string $variableName): string
    {
        return sprintf('Value is not set for variable \'%s\'', $variableName);
    }
}
