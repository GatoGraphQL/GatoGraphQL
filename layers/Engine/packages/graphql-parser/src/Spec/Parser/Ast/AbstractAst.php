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

    private ?GraphQLQueryStringFormatterInterface $graphQLQueryStringFormatter = null;

    final public function setGraphQLQueryStringFormatter(GraphQLQueryStringFormatterInterface $graphQLQueryStringFormatter): void
    {
        $this->graphQLQueryStringFormatter = $graphQLQueryStringFormatter;
    }
    final protected function getGraphQLQueryStringFormatter(): GraphQLQueryStringFormatterInterface
    {
        return $this->graphQLQueryStringFormatter ??= InstanceManagerFacade::getInstance()->getInstance(GraphQLQueryStringFormatterInterface::class);
    }

    public function __construct(protected Location $location)
    {
    }

    final public function __toString(): string
    {
        return $this->asQueryString();
    }

    /**
     * ID to uniquely identify the AST element
     * from all other elements in the GraphQL query.
     *
     * In order to invoke this function,
     * `Document.setAncestorsInAST` must first be invoked.
     */
    final public function getID(): string
    {
        $idUniqueName = $this->getIDUniqueName();
        $parentAST = $this->getParentAST();
        if ($parentAST === null) {
            return $idUniqueName;
        }
        return sprintf(
            $this->getFromParentToSelfIDFormat(),
            $parentAST->getID(),
            $idUniqueName
        );
    }

    abstract protected function getParentAST(): ?AstInterface;

    abstract protected function getIDUniqueName(): string;

    abstract protected function getFromParentToSelfIDFormat(): string;



    public function getLocation(): Location
    {
        return $this->location;
    }

    public function setLocation(Location $location): void
    {
        $this->location = $location;
    }
}
