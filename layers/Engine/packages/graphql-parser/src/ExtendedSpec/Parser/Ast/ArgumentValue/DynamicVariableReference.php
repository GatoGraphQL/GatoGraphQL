<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue;

use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\FeedbackItemProviders\FeedbackItemProvider;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Execution\Context;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\VariableValueAstTrait;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\VariableReference;
use stdClass;

class DynamicVariableReference extends VariableReference
{
    use VariableValueAstTrait;

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
        if (!$this->context->hasVariableValue($this->name)) {
            throw new InvalidRequestException(
                new FeedbackItemResolution(
                    GraphQLSpecErrorFeedbackItemProvider::class,
                    GraphQLSpecErrorFeedbackItemProvider::E_5_8_3,
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
