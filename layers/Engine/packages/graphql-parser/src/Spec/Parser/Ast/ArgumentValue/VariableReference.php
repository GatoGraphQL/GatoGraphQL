<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue;

use LogicException;
use PoP\GraphQLParser\Error\GraphQLErrorMessageProviderInterface;
use PoP\GraphQLParser\Facades\Error\GraphQLErrorMessageProviderFacade;
use PoP\GraphQLParser\FeedbackMessage\FeedbackMessageProvider;
use PoP\GraphQLParser\Spec\Parser\Ast\AbstractAst;
use PoP\GraphQLParser\Spec\Parser\Ast\WithValueInterface;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Services\StandaloneServiceTrait;

class VariableReference extends AbstractAst implements WithValueInterface
{
    use StandaloneServiceTrait;

    private ?GraphQLErrorMessageProviderInterface $graphQLErrorMessageProvider = null;
    private ?FeedbackMessageProvider $feedbackMessageProvider = null;

    final public function setGraphQLErrorMessageProvider(GraphQLErrorMessageProviderInterface $graphQLErrorMessageProvider): void
    {
        $this->graphQLErrorMessageProvider = $graphQLErrorMessageProvider;
    }
    final protected function getGraphQLErrorMessageProvider(): GraphQLErrorMessageProviderInterface
    {
        return $this->graphQLErrorMessageProvider ??= GraphQLErrorMessageProviderFacade::getInstance();
    }
    final public function setFeedbackMessageProvider(FeedbackMessageProvider $feedbackMessageProvider): void
    {
        $this->feedbackMessageProvider = $feedbackMessageProvider;
    }
    final protected function getFeedbackMessageProvider(): FeedbackMessageProvider
    {
        return $this->feedbackMessageProvider ??= InstanceManagerFacade::getInstance()->getInstance(FeedbackMessageProvider::class);
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
     * @throws LogicException
     */
    public function getValue(): mixed
    {
        if ($this->variable === null) {
            throw new LogicException($this->getFeedbackMessageProvider()->getMessage(FeedbackMessageProvider::E_5_8_3, $this->name));
        }

        return $this->variable->getValue();
    }
}
