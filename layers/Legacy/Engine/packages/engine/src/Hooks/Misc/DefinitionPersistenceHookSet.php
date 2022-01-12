<?php

declare(strict_types=1);

namespace PoP\Engine\Hooks\Misc;

use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Engine\Environment;
use PoP\BasicService\AbstractHookSet;

class DefinitionPersistenceHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->getHooksAPI()->addAction(
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
