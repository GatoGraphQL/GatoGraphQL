<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue;

use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Parser\Ast\AbstractAst;
use PoP\GraphQLParser\Spec\Parser\Ast\WithValueInterface;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Services\StandaloneServiceTrait;

class VariableReference extends AbstractAst implements WithValueInterface
{
    use StandaloneServiceTrait;

    private ?GraphQLSpecErrorFeedbackItemProvider $graphQLSpecErrorFeedbackItemProvider = null;

    final public function setGraphQLSpecErrorFeedbackItemProvider(GraphQLSpecErrorFeedbackItemProvider $graphQLSpecErrorFeedbackItemProvider): void
    {
        $this->graphQLSpecErrorFeedbackItemProvider = $graphQLSpecErrorFeedbackItemProvider;
    }
    final protected function getGraphQLSpecErrorFeedbackItemProvider(): GraphQLSpecErrorFeedbackItemProvider
    {
        return $this->graphQLSpecErrorFeedbackItemProvider ??= InstanceManagerFacade::getInstance()->getInstance(GraphQLSpecErrorFeedbackItemProvider::class);
    }

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
