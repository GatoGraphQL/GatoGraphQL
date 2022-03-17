<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue;

use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\VariableReference as UpstreamVariableReference;

class VariableReference extends UpstreamVariableReference
{
    final public function isDynamicVariable(): bool
    {
        return \str_starts_with($this->name, '_');
    }

    /**
     * Override to handle dynamic variables too
     *
     * @throws InvalidRequestException
     */
    public function getValue(): mixed
    {
        if ($this->variable === null && $this->isDynamicVariable()) {
            return $this->getDynamicVariableValue();
        }

        return parent::getValue();
    }

    public function getDynamicVariableValue(): mixed
    {
        if (false) {
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
        
        return null;
    }
}
