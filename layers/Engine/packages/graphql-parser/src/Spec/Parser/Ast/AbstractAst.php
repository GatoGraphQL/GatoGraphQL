<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Query\GraphQLQueryStringFormatterInterface;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Services\StandaloneServiceTrait;

abstract class AbstractAst implements AstInterface, LocatableInterface
{
    use StandaloneServiceTrait;

    protected ?string $queryString = null;

    private ?GraphQLQueryStringFormatterInterface $graphQLQueryStringFormatter = null;

    final public function setGraphQLQueryStringFormatter(GraphQLQueryStringFormatterInterface $graphQLQueryStringFormatter): void
    {
        $this->graphQLQueryStringFormatter = $graphQLQueryStringFormatter;
    }
    final protected function getGraphQLQueryStringFormatter(): GraphQLQueryStringFormatterInterface
    {
        return $this->graphQLQueryStringFormatter ??= InstanceManagerFacade::getInstance()->getInstance(GraphQLQueryStringFormatterInterface::class);
    }

    public function __construct(
        protected readonly Location $location
    ) {
    }

    public function __toString(): string
    {
        return $this->asQueryString();
    }

    public function getLocation(): Location
    {
        return $this->location;
    }

    public function asQueryString(): string
    {
        if ($this->queryString === null) {
            $this->queryString = $this->doAsQueryString();
        }
        return $this->queryString;
    }

    abstract protected function doAsQueryString(): string;
}
