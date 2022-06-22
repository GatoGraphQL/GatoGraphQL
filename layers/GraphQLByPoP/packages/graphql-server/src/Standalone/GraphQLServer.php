<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Standalone;

use GraphQLByPoP\GraphQLQuery\Schema\OperationTypes;
use GraphQLByPoP\GraphQLServer\Module;
use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;
use PoP\ComponentModel\Facades\Engine\EngineFacade;
use PoP\ComponentModel\Module as ComponentModelModule;
use PoP\ComponentModel\ModuleConfiguration as ComponentModelModuleConfiguration;
use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\Exception\Parser\SyntaxErrorException;
use PoP\GraphQLParser\ExtendedSpec\Parser\ParserInterface;
use PoP\GraphQLParser\Spec\Execution\Context;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\GraphQLParser\StaticHelpers\GraphQLParserHelpers;
use PoP\Root\App;
use PoP\Root\HttpFoundation\Response;
use PoPAPI\API\Response\Schemes;
use PoPAPI\API\Routing\RequestNature;
use PoPAPI\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;

class GraphQLServer implements GraphQLServerInterface
{
    private readonly array $moduleClasses;

    /**
     * @param string[] $moduleClasses The component classes to initialize, including those dealing with the schema elements (posts, users, comments, etc)
     * @param array<string,mixed> $moduleClassConfiguration Predefined configuration for the components
     */
    public function __construct(
        array $moduleClasses,
        private readonly array $moduleClassConfiguration = [],
        private readonly ?bool $cacheContainerConfiguration = null,
        private readonly ?string $containerNamespace = null,
        private readonly ?string $containerDirectory = null,
    ) {
        $this->moduleClasses = array_merge(
            $moduleClasses,
            [
                // This is the one Module that is required to produce the GraphQL server.
                // The other classes provide the schema and extra functionality.
                Module::class,
            ]
        );

        $this->initializeApp();
        $appLoader = App::getAppLoader();
        $appLoader->addModuleClassesToInitialize($this->moduleClasses);
        $appLoader->initializeModules();
        $appLoader->bootSystem(
            $this->cacheContainerConfiguration,
            $this->containerNamespace,
            $this->containerDirectory
        );

        // Only after initializing the System Container,
        // we can obtain the configuration (which may depend on hooks)
        $appLoader->addModuleClassConfiguration($this->moduleClassConfiguration);

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
        $appLoader->bootApplicationModules();
    }

    protected function initializeApp(): void
    {
        App::initialize();
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
    public function execute(
        string $query,
        array $variables = [],
        ?string $operationName = null
    ): Response {
        // Override the previous response, if any
        App::regenerateResponse();

        // Override the state
        $appStateManager = App::getAppStateManager();
        $appStateManager->override('query', $query);
        $appStateManager->override('variables', $variables);
        $appStateManager->override('operation-name', $operationName);
        $appStateManager->override('does-api-query-have-errors', null);
        $appStateManager->override('graphql-operation-type', null);
        $appStateManager->override('executable-document-ast-field-fragmentmodels-tuples', null);

        /** @var ComponentModelModuleConfiguration */
        $moduleConfiguration = App::getModule(ComponentModelModule::class)->getConfiguration();
        $appStateManager->override('are-mutations-enabled', $moduleConfiguration->enableMutations());

        try {
            $executableDocument = GraphQLParserHelpers::parseGraphQLQuery(
                $query,
                $variables,
                $operationName
            );
            $appStateManager->override('executable-document-ast', $executableDocument);

            /**
             * Set the operation type and, based on it, if mutations are supported.
             */
            /** @var OperationInterface */
            $requestedOperation = $executableDocument->getRequestedOperation();
            $appStateManager->override('graphql-operation-type', $requestedOperation->getOperationType());
            $appStateManager->override('are-mutations-enabled', $requestedOperation->getOperationType() === OperationTypes::MUTATION);
        } catch (SyntaxErrorException | InvalidRequestException $e) {
            // @todo Show GraphQL error in client
            // ...
            $appStateManager->override('does-api-query-have-errors', true);
        }

        // Generate the data, print the response to buffer, and send headers
        $engine = EngineFacade::getInstance();
        $engine->generateDataAndPrepareResponse();

        // Return the Response, so the client can retrieve content and headers
        return App::getResponse();
    }
}
