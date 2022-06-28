<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\FeedbackItemProviders\FeedbackItemProvider;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Execution\Context;
use PoP\GraphQLParser\Spec\Parser\Ast\AbstractAst;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Enum;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputObject;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\WithValueInterface;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Services\StandaloneServiceTrait;

class Variable extends AbstractAst implements WithValueInterface
{
    use StandaloneServiceTrait;

    protected ?Context $context = null;

    protected bool $hasDefaultValue = false;

    protected InputList|InputObject|Literal|Enum|null $defaultValueAST = null;

    protected OperationInterface $parent;

    public function __construct(
        protected readonly string $name,
        protected readonly string $type,
        protected readonly bool $isRequired,
        protected readonly bool $isArray,
        protected readonly bool $isArrayElementRequired,
        Location $location,
    ) {
        parent::__construct($location);
    }

    protected function doAsQueryString(): string
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
            $this->hasDefaultValue() ? sprintf(' = %s', $this->getDefaultValue()) : ''
        );
    }

    public function setParent(OperationInterface $parent): void
    {
        $this->parent = $parent;
    }

    public function getParent(): OperationInterface
    {
        return $this->parent;
    }

    public function setContext(?Context $context): void
    {
        $this->context = $context;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTypeName(): string
    {
        return $this->type;
    }

    public function isArray(): bool
    {
        return $this->isArray;
    }

    public function isRequired(): bool
    {
        return $this->isRequired;
    }

    public function hasDefaultValue(): bool
    {
        return $this->hasDefaultValue !== null;
    }

    public function getDefaultValue(): mixed
    {
        return $this->defaultValueAST?->getValue();
    }

    public function setDefaultValueAST(InputList|InputObject|Literal|Enum|null $defaultValueAST): void
    {
        $this->hasDefaultValue = true;
        $this->defaultValueAST = $defaultValueAST;
    }

    public function isArrayElementRequired(): bool
    {
        return $this->isArrayElementRequired;
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
            return $this->context->getVariableValue($this->name);
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
