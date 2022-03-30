<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue;

use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\FeedbackItemProviders\FeedbackItemProvider;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Execution\Context;
use PoP\GraphQLParser\Spec\Parser\Ast\AbstractAst;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Enum;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\WithValueInterface;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Services\StandaloneServiceTrait;

class Variable extends AbstractAst implements WithValueInterface
{
    use StandaloneServiceTrait;
    use WithVariableValueTrait;

    protected ?Context $context = null;

    protected bool $hasDefaultValue = false;

    protected InputList|InputObject|Literal|Enum|null $defaultValue = null;

    protected OperationInterface $parent;

    public function __construct(
        protected string $name,
        protected string $type,
        protected bool $isRequired,
        protected bool $isArray,
        protected bool $isArrayElementRequired,
        Location $location,
    ) {
        parent::__construct($location);
    }

    public function asQueryString(): string
    {
        $strType = $this->type;
        if ($this->isArray) {
            if ($this->isArrayElementRequired) {
                $strType .= '!';
            }
            $strType = sprintf('[%s]', $strType);
        }
        if ($this->isRequired) {
            $strType .= '!';
        }
        return sprintf(
            '$%s: %s%s',
            $this->name,
            $strType,
            $this->hasDefaultValue ? sprintf(' = %s', $this->defaultValue) : ''
        );
    }

    public function setParent(OperationInterface $parent): void
    {
        $this->parent = $parent;
    }

    public function setContext(?Context $context): void
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
        return $this->isRequired;
    }

    public function setRequired(bool $isRequired): void
    {
        $this->isRequired = $isRequired;
    }

    public function hasDefaultValue(): bool
    {
        return $this->hasDefaultValue;
    }

    public function getDefaultValue(): InputList|InputObject|Literal|Enum|null
    {
        return $this->defaultValue;
    }

    public function setDefaultValue(InputList|InputObject|Literal|Enum|null $defaultValue): void
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
     * @return InputList|InputObject|Literal|Enum|null
     * @throws InvalidRequestException
     */
    public function getValue(): mixed
    {
        if ($this->context === null) {
            throw new InvalidRequestException(
                new FeedbackItemResolution(
                    FeedbackItemProvider::class,
                    FeedbackItemProvider::E2,
                    [
                        $this->name,
                    ]
                ),
                $this->getLocation()
            );
        }
        if ($this->context->hasVariableValue($this->name)) {
            $variableValue = $this->context->getVariableValue($this->name);
            return $this->convertVariableValueToAst(
                $variableValue,
                $this->getLocation()
            );
        }
        if ($this->hasDefaultValue()) {
            return $this->getDefaultValue();
        }
        if ($this->isRequired()) {
            throw new InvalidRequestException(
                new FeedbackItemResolution(
                    GraphQLSpecErrorFeedbackItemProvider::class,
                    GraphQLSpecErrorFeedbackItemProvider::E_5_8_5,
                    [
                        $this->name,
                    ]
                ),
                $this->getLocation()
            );
        }
        return null;
    }
}
