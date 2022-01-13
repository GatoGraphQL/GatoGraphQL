<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Root\Hooks\AbstractHookSet;
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_System_Engine_ModuleDefinitionHooks extends AbstractHookSet
{
    protected function init(): void
    {
        \PoP\Root\App::getHookManager()->addAction(
            'PoP:system:save-definition-file',
            array($this, 'persistDefinitions')
        );
    }
    public function persistDefinitions()
    {
        DefinitionManagerFacade::getInstance()->maybeStoreDefinitionsPersistently();
    }
}

/**
 * Initialization
 */
new PoP_System_Engine_ModuleDefinitionHooks(
    HooksAPIFacade::getInstance(),
    TranslationAPIFacade::getInstance()
);
