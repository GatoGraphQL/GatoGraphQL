<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue;

use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLExtendedSpecFeedbackItemProvider;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLExtendedSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Execution\Context;

class DynamicVariableReference extends AbstractDynamicVariableReference
{
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
        /**
         * @todo Remove this temporary hack to support expressions (until the engine is migrated to AST).
         *
         * This temporary logic returns value "$__expressionName",
         * to be processed as an expression on runtime.
         *
         * Switched from "%{...}%" to "$__..."
         * @todo Convert expressions from "$__" to "$"
         */
        if (str_starts_with($this->name, '__')) {
            return '$' . $this->name;
        }

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
        return $this->context->getVariableValue($this->name);
    }
}
