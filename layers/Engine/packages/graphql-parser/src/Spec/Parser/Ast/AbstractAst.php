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
     * By default, the location of the element in the
     * query is used.
     */
    public function getID(): string
    {
        $class = get_called_class();
        $className = substr($class, strrpos($class, '\\') + 1);
        return sprintf(
            '%s([%s,%s])',
            $className,
            $this->getLocation()->getLine(),
            $this->getLocation()->getColumn()
        );
    }



    public function getLocation(): Location
    {
        return $this->location;
    }
}
