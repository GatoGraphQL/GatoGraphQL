<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\State;

use PoP\Root\State\AbstractAppStateProvider;
use PoPCMSSchema\Users\Routing\RequestNature;

class AppStateProvider extends AbstractAppStateProvider
{
    public function augment(array &$state): void
    {
        $nature = $state['nature'];
        $state['routing']['is-user'] = $nature === RequestNature::USER;
    }
}
