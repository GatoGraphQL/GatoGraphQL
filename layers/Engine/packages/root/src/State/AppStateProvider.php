<?php

declare(strict_types=1);

namespace PoP\Root\State;

use PoP\Root\Routing\RequestNature;

class AppStateProvider extends AbstractAppStateProvider
{
    /**
     * @param array<string,mixed> $state
     */
    public function initialize(array &$state): void
    {
        $state['nature'] = RequestNature::GENERIC;
        $state['route'] = '';
        $state['routing'] = [];
    }

    /**
     * @param array<string,mixed> $state
     */
    public function augment(array &$state): void
    {
        $nature = $state['nature'];
        $state['routing']['is-generic'] = $nature === RequestNature::GENERIC;
    }
}
