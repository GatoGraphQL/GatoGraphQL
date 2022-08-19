<?php

declare(strict_types=1);

namespace PoPCMSSchema\Pages\State;

use PoP\Root\State\AbstractAppStateProvider;
use PoPCMSSchema\Pages\Routing\RequestNature;

class AppStateProvider extends AbstractAppStateProvider
{
    /**
     * @param array<string,mixed> $state
     */
    public function augment(array &$state): void
    {
        $nature = $state['nature'];
        $state['routing']['is-page'] = $nature === RequestNature::PAGE;
    }
}
