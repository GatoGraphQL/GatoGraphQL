<?php
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Hooks\AbstractHookSet;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Translation\Facades\TranslationAPIFacade;

class PoP_System_Engine_ModuleDefinitionHooks extends AbstractHookSet
{
    protected function init(): void
    {
        $this->getHooksAPI()->addAction(
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
