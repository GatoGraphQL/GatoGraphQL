<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue;

use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\FeedbackMessageProviders\GraphQLSpecErrorMessageProvider;
use PoP\GraphQLParser\Spec\Parser\Ast\AbstractAst;
use PoP\GraphQLParser\Spec\Parser\Ast\WithValueInterface;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Services\StandaloneServiceTrait;

class VariableReference extends AbstractAst implements WithValueInterface
{
    use StandaloneServiceTrait;

    private ?GraphQLSpecErrorMessageProvider $graphQLSpecErrorMessageProvider = null;

    final public function setGraphQLSpecErrorMessageProvider(GraphQLSpecErrorMessageProvider $graphQLSpecErrorMessageProvider): void
    {
        $this->graphQLSpecErrorMessageProvider = $graphQLSpecErrorMessageProvider;
    }
    final protected function getGraphQLSpecErrorMessageProvider(): GraphQLSpecErrorMessageProvider
    {
        return $this->graphQLSpecErrorMessageProvider ??= InstanceManagerFacade::getInstance()->getInstance(GraphQLSpecErrorMessageProvider::class);
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
                $this->getGraphQLSpecErrorMessageProvider()->getMessage(GraphQLSpecErrorMessageProvider::E_5_8_3, $this->name),
                $this->getGraphQLSpecErrorMessageProvider()->getNamespacedCode(GraphQLSpecErrorMessageProvider::E_5_8_3),
                $this->getLocation()
            );
        }

        return $this->variable->getValue();
    }
}
