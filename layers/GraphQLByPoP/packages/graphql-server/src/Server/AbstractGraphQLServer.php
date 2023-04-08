<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Server;

use PoPAPI\API\HelperServices\ApplicationStateFillerServiceInterface;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Engine\EngineInterface;
use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\HttpFoundation\Response;
use PoP\Root\Services\StandaloneServiceTrait;

abstract class AbstractGraphQLServer implements GraphQLServerInterface
{
    use StandaloneServiceTrait;

    private ?ApplicationStateFillerServiceInterface $applicationStateFillerService = null;
    private ?EngineInterface $engine = null;

    final public function setApplicationStateFillerService(ApplicationStateFillerServiceInterface $applicationStateFillerService): void
    {
        $this->applicationStateFillerService = $applicationStateFillerService;
    }
    final protected function getApplicationStateFillerService(): ApplicationStateFillerServiceInterface
    {
        /** @var ApplicationStateFillerServiceInterface */
        return $this->applicationStateFillerService ??= InstanceManagerFacade::getInstance()->getInstance(ApplicationStateFillerServiceInterface::class);
    }
    final public function setEngine(EngineInterface $engine): void
    {
        $this->engine = $engine;
    }
    final protected function getEngine(): EngineInterface
    {
        /** @var EngineInterface */
        return $this->engine ??= InstanceManagerFacade::getInstance()->getInstance(EngineInterface::class);
    }

    /**
     * The basic state for executing GraphQL queries is already set.
     * In addition, inject the actual GraphQL query and variables,
     * build the AST, and generate and print the data.
     *
     * @param array<string,mixed> $variables
     */
    public function execute(
        string|ExecutableDocument $queryOrExecutableDocument,
        array $variables = [],
        ?string $operationName = null
    ): Response {
        // Override the previous response, if any
        App::regenerateResponse();

        $engine = $this->getEngine();
        $engine->initializeState();

        $this->getApplicationStateFillerService()->defineGraphQLQueryVarsInApplicationState(
            $queryOrExecutableDocument,
            $variables,
            $operationName,
        );

        // Generate the data, print the response to buffer, and send headers
        $engine->generateDataAndPrepareResponse();

        return App::getResponse();
    }
}
