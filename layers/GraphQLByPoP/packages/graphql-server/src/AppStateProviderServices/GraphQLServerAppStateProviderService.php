<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\AppStateProviderServices;

use PoPAPI\API\Response\Schemes;
use PoPAPI\API\Routing\RequestNature;
use PoPAPI\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Services\StandaloneServiceTrait;

class GraphQLServerAppStateProviderService implements GraphQLServerAppStateProviderServiceInterface
{
    use StandaloneServiceTrait;

    private ?GraphQLDataStructureFormatter $graphQLDataStructureFormatter = null;

    final public function setGraphQLDataStructureFormatter(GraphQLDataStructureFormatter $graphQLDataStructureFormatter): void
    {
        $this->graphQLDataStructureFormatter = $graphQLDataStructureFormatter;
    }
    final protected function getGraphQLDataStructureFormatter(): GraphQLDataStructureFormatter
    {
        /** @var GraphQLDataStructureFormatter */
        return $this->graphQLDataStructureFormatter ??= InstanceManagerFacade::getInstance()->getInstance(GraphQLDataStructureFormatter::class);
    }

    /**
     * The required state to execute GraphQL queries.
     *
     * @return array<string,mixed>
     */
    public function getGraphQLRequestAppState(): array
    {
        return [
            'scheme' => Schemes::API,
            'datastructure' => $this->getGraphQLDataStructureFormatter()->getName(),
            'nature' => RequestNature::QUERY_ROOT,
            'query' => null,
        ];
    }
}
