<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Standalone;

use GraphQLByPoP\GraphQLQuery\Facades\GraphQLQueryConvertorFacade;
use GraphQLByPoP\GraphQLQuery\Schema\OperationTypes;
use PoP\Engine\Facades\Engine\EngineFacade;
use PoP\Root\App;
use PoPAPI\API\Facades\FieldQueryConvertorFacade;
use PoPAPI\API\Response\Schemes;
use PoPAPI\API\Routing\RequestNature;
use PoPAPI\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;

class GraphQLServer
{
    private array $componentClasses;

    public function __construct(
        array $componentClasses,
        private array $componentClassConfiguration = [],
        private ?bool $cacheContainerConfiguration = null,
        private ?string $containerNamespace = null,
        private ?string $containerDirectory = null,
    ) {
        $this->componentClasses = array_merge(
            $componentClasses,
            [
                \GraphQLByPoP\GraphQLServer\Component::class,
            ]
        );

        App::initialize();
        $appLoader = App::getAppLoader();
        $appLoader->addComponentClassesToInitialize($this->componentClasses);
        $appLoader->initializeComponents();
        $appLoader->bootSystem(
            $this->cacheContainerConfiguration,
            $this->containerNamespace,
            $this->containerDirectory
        );

        // Only after initializing the System Container,
        // we can obtain the configuration (which may depend on hooks)
        $appLoader->addComponentClassConfiguration($this->componentClassConfiguration);

        // Boot the application
        $appLoader->bootApplication(
            $this->cacheContainerConfiguration,
            $this->containerNamespace,
            $this->containerDirectory
        );

        // After booting the application, we can access the Application Container services
        // Explicitly set the required state to execute GraphQL queries
        $appLoader->setInitialAppState($this->getGraphQLRequestAppState());

        // Finally trigger booting the components
        $appLoader->bootApplicationComponents();
    }

    /**
     * The required state to execute GraphQL queries.
     *
     * @return array<string,mixed>
     */
    protected function getGraphQLRequestAppState(): array
    {
        return [
            'scheme' => Schemes::API,
            'datastructure' => $this->getGraphQLDataStructureFormatter()->getName(),
            'nature' => RequestNature::QUERY_ROOT,
            'only-fieldname-as-outputkey' => true,
            'standard-graphql' => true,
            'query' => '{}', // Added to avoid error message "The query in the body is empty"
        ];
    }

    protected function getGraphQLDataStructureFormatter(): GraphQLDataStructureFormatter
    {
        return App::getContainer()->get(GraphQLDataStructureFormatter::class);
    }

    /**
     * The basic state for executing GraphQL queries is already set.
     * In addition, inject the actual GraphQL query and variables,
     * build the AST, and generate and print the data.
     *
     * @param array<string,mixed> $variables
     */
    public function execute(string $query, array $variables = []): void
    {
        $appStateManager = App::getAppStateManager();
        $appStateManager->override('query', $query);
        $appStateManager->override('variables', $variables);

        [$operationType, $fieldQuery] = GraphQLQueryConvertorFacade::getInstance()->convertFromGraphQLToFieldQuery(
            $query,
            $variables,
        );
        $appStateManager->override('graphql-operation-type', $operationType);
        $appStateManager->override('are-mutations-enabled', $operationType === OperationTypes::MUTATION);

        $fieldQueryConvertor = FieldQueryConvertorFacade::getInstance();
        $fieldQuerySet = $fieldQueryConvertor->convertAPIQuery($fieldQuery);
        $appStateManager->override('executable-query', $fieldQuerySet->getExecutableFieldQuery());
        if ($fieldQuerySet->areRequestedAndExecutableFieldQueriesDifferent()) {
            $appStateManager->override('requested-query', $fieldQuerySet->getRequestedFieldQuery());
        }

        $engine = EngineFacade::getInstance();
        $engine->outputResponse();
    }
}
