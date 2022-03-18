<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue;

use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLExtendedSpecFeedbackItemProvider;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLExtendedSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Execution\Context;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\WithVariableValueTrait;

class DynamicVariableReference extends AbstractDynamicVariableReference
{
    use WithVariableValueTrait;

    private ?Context $context = null;

    public function setContext(?Context $context): void
    {
        $this->context = $context;
    }

    /**
     * Get the value directly from the context
     * as to handle dynamic variables
     *
     * @throws InvalidRequestException
     *
     * @todo Review this logic, it must not work! How to pass the context to the AST? Checking for the variable must instead be done against $variable in ->resolveValue
     */
    public function getValue(): mixed
    {
        if ($this->context === null) {
            throw new InvalidRequestException(
                new FeedbackItemResolution(
                    GraphQLExtendedSpecFeedbackItemProvider::class,
                    GraphQLExtendedSpecFeedbackItemProvider::E1,
                    [
                        $this->name,
                    ]
                ),
                $this->getLocation()
            );
        }
        if (!$this->context->hasVariableValue($this->name)) {
            throw new InvalidRequestException(
                new FeedbackItemResolution(
                    GraphQLExtendedSpecErrorFeedbackItemProvider::class,
                    GraphQLExtendedSpecErrorFeedbackItemProvider::E_5_8_3,
                    [
                        $this->name,
                    ]
                ),
                $this->getLocation()
            );
        }
        $variableValue = $this->context->getVariableValue($this->name);
        return $this->convertVariableValueToAst(
            $variableValue,
            $this->getLocation()
        );
    }
}
