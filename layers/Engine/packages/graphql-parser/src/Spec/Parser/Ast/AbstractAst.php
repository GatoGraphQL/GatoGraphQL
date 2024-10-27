<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Query\GraphQLQueryStringFormatterInterface;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Services\StandaloneServiceTrait;

abstract class AbstractAst implements AstInterface
{
    use StandaloneServiceTrait;

    protected ?string $queryString = null;
    protected ?string $astNodeString = null;

    private ?GraphQLQueryStringFormatterInterface $graphQLQueryStringFormatter = null;

    final protected function getGraphQLQueryStringFormatter(): GraphQLQueryStringFormatterInterface
    {
        if ($this->graphQLQueryStringFormatter === null) {
            /** @var GraphQLQueryStringFormatterInterface */
            $graphQLQueryStringFormatter = InstanceManagerFacade::getInstance()->getInstance(GraphQLQueryStringFormatterInterface::class);
            $this->graphQLQueryStringFormatter = $graphQLQueryStringFormatter;
        }
        return $this->graphQLQueryStringFormatter;
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

    final public function asQueryString(): string
    {
        if ($this->queryString === null) {
            $this->queryString = $this->doAsQueryString();
        }
        return $this->queryString;
    }

    abstract protected function doAsQueryString(): string;

    final public function asASTNodeString(): string
    {
        if ($this->astNodeString === null) {
            $this->astNodeString = $this->doAsASTNodeString();
        }
        return $this->astNodeString;
    }

    abstract protected function doAsASTNodeString(): string;
}
