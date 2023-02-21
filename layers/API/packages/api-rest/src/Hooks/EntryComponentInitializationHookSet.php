<?php

declare(strict_types=1);

namespace PoPAPI\RESTAPI\Hooks;

use PoPAPI\API\Response\Schemes as APISchemes;
use PoPAPI\RESTAPI\DataStructureFormatters\RESTDataStructureFormatter;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\Engine\Engine;
use PoP\Root\App;
use PoP\Root\Exception\ImpossibleToHappenException;
use PoP\Root\Hooks\AbstractHookSet;

class EntryComponentInitializationHookSet extends AbstractHookSet
{
    private ?RESTDataStructureFormatter $restDataStructureFormatter = null;
    private ?ApplicationStateFillerServiceInterface $applicationStateFillerService = null;

    final public function setRESTDataStructureFormatter(RESTDataStructureFormatter $restDataStructureFormatter): void
    {
        $this->restDataStructureFormatter = $restDataStructureFormatter;
    }
    final protected function getRESTDataStructureFormatter(): RESTDataStructureFormatter
    {
        /** @var RESTDataStructureFormatter */
        return $this->restDataStructureFormatter ??= $this->instanceManager->getInstance(RESTDataStructureFormatter::class);
    }
    final public function setApplicationStateFillerService(ApplicationStateFillerServiceInterface $applicationStateFillerService): void
    {
        $this->applicationStateFillerService = $applicationStateFillerService;
    }
    final protected function getApplicationStateFillerService(): ApplicationStateFillerServiceInterface
    {
        /** @var ApplicationStateFillerServiceInterface */
        return $this->applicationStateFillerService ??= $this->instanceManager->getInstance(ApplicationStateFillerServiceInterface::class);
    }

    protected function init(): void
    {
        App::addAction(
            Engine::HOOK_ENTRY_COMPONENT_INITIALIZATION,
            $this->initializeEntryComponent(...)
        );
    }

    public function initializeEntryComponent(Component $entryComponent): void
    {
        if (App::getState('scheme') !== APISchemes::API
            || App::getState('datastructure') !== $this->getRestDataStructureFormatter()->getName()
        ) {
            return;
        }

        /**
         * If no query was requested, and the entry component defines
         * a query (eg: REST), then parse it and set as the app query
         * to resolve
         */
        $executableDocument = App::getState('executable-document-ast');
        if ($executableDocument !== null || !isset($entryComponent->atts['query'])) {
            throw new ImpossibleToHappenException(
                $this->__('When requesting a REST resource, the GraphQL AST must be built from the entry component\'s "query" string', 'component-model')
            );
        }
    }
}
