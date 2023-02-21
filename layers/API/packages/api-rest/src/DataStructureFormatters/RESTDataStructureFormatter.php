<?php

declare(strict_types=1);

namespace PoPAPI\RESTAPI\DataStructureFormatters;

use PoPAPI\APIMirrorQuery\DataStructureFormatters\MirrorQueryDataStructureFormatter;
use PoPAPI\API\QueryParsing\GraphQLParserHelperServiceInterface;
use PoP\ComponentModel\Engine\EngineInterface;

class RESTDataStructureFormatter extends MirrorQueryDataStructureFormatter
{
    private ?EngineInterface $engine = null;
    private ?GraphQLParserHelperServiceInterface $graphQLParserHelperService = null;

    final public function setEngine(EngineInterface $engine): void
    {
        $this->engine = $engine;
    }
    final protected function getEngine(): EngineInterface
    {
        /** @var EngineInterface */
        return $this->engine ??= $this->instanceManager->getInstance(EngineInterface::class);
    }
    final public function setGraphQLParserHelperService(GraphQLParserHelperServiceInterface $graphQLParserHelperService): void
    {
        $this->graphQLParserHelperService = $graphQLParserHelperService;
    }
    final protected function getGraphQLParserHelperService(): GraphQLParserHelperServiceInterface
    {
        /** @var GraphQLParserHelperServiceInterface */
        return $this->graphQLParserHelperService ??= $this->instanceManager->getInstance(GraphQLParserHelperServiceInterface::class);
    }

    public function getName(): string
    {
        return 'rest';
    }
}
