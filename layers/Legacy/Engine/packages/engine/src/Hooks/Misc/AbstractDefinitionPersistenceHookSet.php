<?php

declare(strict_types=1);

namespace PoP\Engine\Hooks\Misc;

use PoP\Root\App;
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Engine\Environment;
use PoP\Root\Hooks\AbstractHookSet;

abstract class AbstractDefinitionPersistenceHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addAction(
            $this->getHookName(),
            $this->maybePersist(...)
        );
    }
    
    public function maybePersist(): void
    {
        if (!Environment::disablePersistingDefinitionsOnEachRequest()) {
            DefinitionManagerFacade::getInstance()->maybeStoreDefinitionsPersistently();
        }
    }
    
    abstract protected function getHookName(): string;
}
