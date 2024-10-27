<?php

declare(strict_types=1);

namespace PoPAPI\RESTAPI\Hooks;

use PoPAPI\API\HelperServices\ApplicationStateFillerServiceInterface;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoPAPI\RESTAPI\DataStructureFormatters\RESTDataStructureFormatter;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\Engine\EngineHookNames;
use PoP\Root\App;
use PoP\Root\Exception\ImpossibleToHappenException;
use PoP\Root\Hooks\AbstractHookSet;

class EntryComponentInitializationHookSet extends AbstractHookSet
{
    private ?RESTDataStructureFormatter $restDataStructureFormatter = null;
    private ?ApplicationStateFillerServiceInterface $applicationStateFillerService = null;

    final protected function getRESTDataStructureFormatter(): RESTDataStructureFormatter
    {
        if ($this->restDataStructureFormatter === null) {
            /** @var RESTDataStructureFormatter */
            $restDataStructureFormatter = $this->instanceManager->getInstance(RESTDataStructureFormatter::class);
            $this->restDataStructureFormatter = $restDataStructureFormatter;
        }
        return $this->restDataStructureFormatter;
    }
    final protected function getApplicationStateFillerService(): ApplicationStateFillerServiceInterface
    {
        if ($this->applicationStateFillerService === null) {
            /** @var ApplicationStateFillerServiceInterface */
            $applicationStateFillerService = $this->instanceManager->getInstance(ApplicationStateFillerServiceInterface::class);
            $this->applicationStateFillerService = $applicationStateFillerService;
        }
        return $this->applicationStateFillerService;
    }

    protected function init(): void
    {
        App::addAction(
            EngineHookNames::ENTRY_COMPONENT_INITIALIZATION,
            $this->initializeEntryComponent(...)
        );
    }

    public function initializeEntryComponent(Component $entryComponent): void
    {
        if (
            App::getState('scheme') !== APISchemes::API
            || App::getState('datastructure') !== $this->getRestDataStructureFormatter()->getName()
        ) {
            return;
        }

        /**
         * If no query was requested, and the entry component defines
         * a query (eg: REST), then parse it and set as the app query
         * to resolve
         */
        if (!isset($entryComponent->atts['query'])) {
            throw new ImpossibleToHappenException(
                $this->__('When requesting a REST resource, the GraphQL AST must be built from the entry component\'s "query" string', 'component-model')
            );
        }

        $graphQLQuery = $entryComponent->atts['query'];
        $this->getApplicationStateFillerService()->defineGraphQLQueryVarsInApplicationState($graphQLQuery);
    }
}
