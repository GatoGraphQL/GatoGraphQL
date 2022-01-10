<?php

declare(strict_types=1);

namespace PoPSchema\Users\State;

use PoP\Root\State\AbstractAppStateProvider;
use PoPSchema\Users\Routing\RouteNatures;

class AppStateProvider extends AbstractAppStateProvider
{
    public function augment(array &$state): void
    {
        $nature = $state['nature'];
        $state['routing-state']['is-user'] = $nature === RouteNatures::USER;
    }
}
