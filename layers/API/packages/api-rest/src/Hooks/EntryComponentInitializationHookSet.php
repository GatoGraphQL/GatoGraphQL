<?php

declare(strict_types=1);

namespace PoPAPI\RESTAPI\Hooks;

use PoPAPI\API\Response\Schemes as APISchemes;
use PoPAPI\RESTAPI\DataStructureFormatters\RESTDataStructureFormatter;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\Engine\Engine;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

class EntryComponentInitializationHookSet extends AbstractHookSet
{
    private ?RESTDataStructureFormatter $restDataStructureFormatter = null;

    final public function setRESTDataStructureFormatter(RESTDataStructureFormatter $restDataStructureFormatter): void
    {
        $this->restDataStructureFormatter = $restDataStructureFormatter;
    }
    final protected function getRESTDataStructureFormatter(): RESTDataStructureFormatter
    {
        /** @var RESTDataStructureFormatter */
        return $this->restDataStructureFormatter ??= $this->instanceManager->getInstance(RESTDataStructureFormatter::class);
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
    }
}
