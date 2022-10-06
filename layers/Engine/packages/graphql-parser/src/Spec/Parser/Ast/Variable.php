<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Exception\InvalidRequestException;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Execution\Context;
use PoP\GraphQLParser\Spec\Parser\Ast\AbstractAst;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Enum;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputList;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\InputObject;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\WithDirectivesTrait;
use PoP\GraphQLParser\Spec\Parser\Ast\WithValueInterface;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\Exception\ShouldNotHappenException;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Services\StandaloneServiceTrait;

class Variable extends AbstractAst implements WithValueInterface
{
    use StandaloneServiceTrait;
    use WithDirectivesTrait;

    protected ?Context $context = null;

    protected bool $hasDefaultValue = false;

    protected InputList|InputObject|Literal|Enum|null $defaultValueAST = null;

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
        $defaultValue = '';
        if ($this->hasDefaultValue()) {
            /** @var InputList|InputObject|Literal|Enum */
            $defaultValueAST = $this->getDefaultValueAST();
            $defaultValue = sprintf(' = %s', $defaultValueAST->asQueryString());
        }
        return sprintf(
            '$%s: %s%s',
            $this->name,
            $strType,
            $defaultValue
        );
    }

    protected function doAsASTNodeString(): string
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
        $defaultValue = '';
        if ($this->hasDefaultValue()) {
            /** @var InputList|InputObject|Literal|Enum */
            $defaultValueAST = $this->getDefaultValueAST();
            $defaultValue = sprintf(' = %s', $defaultValueAST->asQueryString());
        }
        return sprintf(
            '$%s: %s%s',
            $this->name,
            $strType,
            $defaultValue
        );
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
        return $this->hasDefaultValue;
    }

    public function getDefaultValue(): mixed
    {
        return $this->defaultValueAST?->getValue();
    }

    public function setDefaultValueAST(InputList|InputObject|Literal|Enum|null $defaultValueAST): void
    {
        $this->hasDefaultValue = $defaultValueAST !== null;
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
     * @throws ShouldNotHappenException When context not set
     */
    public function getValue(): mixed
    {
        if ($this->context === null) {
            throw new ShouldNotHappenException(
                sprintf(
                    $this->__('Context has not been set for Variable object (with name \'%s\')', 'graphql-server'),
                    $this->name,
                )
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
                $this
            );
        }
        return null;
    }

    public function getDefaultValueAST(): InputList|InputObject|Literal|Enum|null
    {
        return $this->defaultValueAST;
    }
}
