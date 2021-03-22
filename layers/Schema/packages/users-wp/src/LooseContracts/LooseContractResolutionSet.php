<?php

declare(strict_types=1);

namespace PoPSchema\UsersWP\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractResolutionSet;

class LooseContractResolutionSet extends AbstractLooseContractResolutionSet
{
    protected function resolveContracts(): void
    {
        $this->nameResolver->implementNames([
            'popcms:dbcolumn:orderby:users:name' => 'name',
            'popcms:dbcolumn:orderby:users:id' => 'ID',
            'popcms:dbcolumn:orderby:users:registrationdate' => 'registered',
        ]);
    }
}
