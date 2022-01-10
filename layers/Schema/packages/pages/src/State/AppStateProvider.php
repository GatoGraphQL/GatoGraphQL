<?php

declare(strict_types=1);

namespace PoPSchema\Pages\State;

use PoP\Root\State\AbstractAppStateProvider;
use PoPSchema\Pages\Routing\RouteNatures;

class AppStateProvider extends AbstractAppStateProvider
{
    public function augment(array &$state): void
    {
        $nature = $state['nature'];
        $state['routing-state']['is-page'] = $nature === RouteNatures::PAGE;
    }
}
