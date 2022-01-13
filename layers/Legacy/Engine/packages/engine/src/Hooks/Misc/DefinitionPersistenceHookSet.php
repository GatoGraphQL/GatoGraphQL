<?php

declare(strict_types=1);

namespace PoP\Engine\Hooks\Misc;

use PoP\Root\App;
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Engine\Environment;
use PoP\Root\Hooks\AbstractHookSet;

class DefinitionPersistenceHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::getHookManager()->addAction(
            'popcms:shutdown',
            array($this, 'maybePersist')
        );
    }
    public function maybePersist(): void
    {
        if (!Environment::disablePersistingDefinitionsOnEachRequest()) {
            DefinitionManagerFacade::getInstance()->maybeStoreDefinitionsPersistently();
        }
    }
}
