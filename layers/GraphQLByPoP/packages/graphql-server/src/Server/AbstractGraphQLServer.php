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
        if ($this->applicationStateFillerService === null) {
            /** @var ApplicationStateFillerServiceInterface */
            $applicationStateFillerService = InstanceManagerFacade::getInstance()->getInstance(ApplicationStateFillerServiceInterface::class);
            $this->applicationStateFillerService = $applicationStateFillerService;
        }
        return $this->applicationStateFillerService;
    }
    final public function setEngine(EngineInterface $engine): void
    {
        $this->engine = $engine;
    }
    final protected function getEngine(): EngineInterface
    {
        if ($this->engine === null) {
            /** @var EngineInterface */
            $engine = InstanceManagerFacade::getInstance()->getInstance(EngineInterface::class);
            $this->engine = $engine;
        }
        return $this->engine;
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
        // Keep the current response, to be restored later on
        $currentResponse = App::getResponse();

        // Set a new Response into the AppState
        App::setResponse(new Response());

        /**
         * The GraphQL query is validated in
         * `defineGraphQLQueryVarsInApplicationState`,
         * so before then we need to create new instances
         * for the Document/Feedback stores as errors
         * can be added to them
         *
         * @see `generateDataAndPrepareResponse` in layers/Engine/packages/component-model/src/Engine/Engine.php
         */
        App::generateAndStackFeedbackStore();
        App::generateAndStackTracingStore();

        $this->getApplicationStateFillerService()->defineGraphQLQueryVarsInApplicationState(
            $queryOrExecutableDocument,
            $variables,
            $operationName,
        );

        /**
         * Create and stack a new Response object, to be
         * used during this processing
         */
        $this->getEngine()->generateDataAndPrepareResponse(
            $this->areFeedbackAndTracingStoresAlreadyCreated()
        );

        $response = App::getResponse();

        // Restore the previous Response
        App::setResponse($currentResponse);

        return $response;
    }

    /**
     * Function kept for documentation purposes.
     *
     * Since already calling `App::generateAndStackFeedbackStore()`
     * in method above, there's no need Engine to execute that logic.
     *
     * It is needed, as the store regeneration must happen
     * before the GraphQL query is validated.
     */
    final protected function areFeedbackAndTracingStoresAlreadyCreated(): bool
    {
        return true;
    }
}
