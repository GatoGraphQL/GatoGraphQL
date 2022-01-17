<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommonsWP\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractResolutionSet;

class LooseContractResolutionSet extends AbstractLooseContractResolutionSet
{
    protected function resolveContracts(): void
    {
        $this->getNameResolver()->implementNames([
            'popcms:option:dateFormat' => 'date_format',
        ]);
    }
}
