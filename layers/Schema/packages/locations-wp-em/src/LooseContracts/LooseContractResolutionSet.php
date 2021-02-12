<?php

declare(strict_types=1);

namespace PoPSchema\LocationsWPEM\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractResolutionSet;

class LooseContractResolutionSet extends AbstractLooseContractResolutionSet
{
    protected function resolveContracts()
    {
        $this->nameResolver->implementNames([
            'popcomponent:locations:dbcolumn:orderby:locations:name' => 'location_name',
        ]);
        // Watch out! This is LocationMutationsWPEM!
        // But the package still does not exist!
        $this->nameResolver->implementNames([
            'popcomponent:addlocations:option:locationDefaultCountry' => 'dbem_location_default_country',
        ]);
    }
}
