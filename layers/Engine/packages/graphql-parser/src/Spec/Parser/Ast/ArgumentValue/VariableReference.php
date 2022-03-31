<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue;

use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Parser\Ast\AbstractAst;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\Variable;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Services\StandaloneServiceTrait;

class VariableReference extends AbstractAst implements VariableReferenceInterface
{
    use StandaloneServiceTrait;

    protected InputList|InputObject|Argument $parent;

    public function __construct(
        protected string $name,
        protected ?Variable $variable,
        Location $location,
    ) {
        parent::__construct($location);
    }

    public function asQueryString(): string
    {
        return sprintf(
            '$%s',
            $this->name
        );
    }

    public function setParent(InputList|InputObject|Argument $parent): void
    {
        $this->parent = $parent;
    }

    public function getParent(): InputList|InputObject|Argument
    {
        return $this->parent;
    }

    public function getVariable(): ?Variable
    {
        return $this->variable;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the value from the context or from the variable
     *
     * @throws InvalidRequestException
     */
    public function getValue(): mixed
    {
        if ($this->variable === null) {
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

        return $this->variable->getValue();
    }
}
