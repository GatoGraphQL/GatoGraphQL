<?php

declare(strict_types=1);

namespace PoPSchema\UserState\State;

use PoP\Root\State\AbstractAppStateProvider;

class AppStateProvider extends AbstractAppStateProvider
{
    public function initialize(array &$state): void
    {
        ApplicationStateUtils::setUserStateVars($state);
    }
}
