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

    final protected function getEngine(): EngineInterface
    {
        if ($this->engine === null) {
            /** @var EngineInterface */
            $engine = $this->instanceManager->getInstance(EngineInterface::class);
            $this->engine = $engine;
        }
        return $this->engine;
    }
    final protected function getGraphQLParserHelperService(): GraphQLParserHelperServiceInterface
    {
        if ($this->graphQLParserHelperService === null) {
            /** @var GraphQLParserHelperServiceInterface */
            $graphQLParserHelperService = $this->instanceManager->getInstance(GraphQLParserHelperServiceInterface::class);
            $this->graphQLParserHelperService = $graphQLParserHelperService;
        }
        return $this->graphQLParserHelperService;
    }

    public function getName(): string
    {
        return 'rest';
    }
}
