<?php

declare(strict_types=1);

namespace PoP\ApplicationWP\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractResolutionSet;

class LooseContractResolutionSet extends AbstractLooseContractResolutionSet
{
    protected function resolveContracts(): void
    {
        $this->nameResolver->implementNames([
            'popcms:option:limit' => 'posts_per_page',
        ]);
    }
}
