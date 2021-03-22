<?php

declare(strict_types=1);

namespace PoPSchema\UsersWP\Conditional\CustomPosts\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractResolutionSet;

class LooseContractResolutionSet extends AbstractLooseContractResolutionSet
{
    protected function resolveContracts(): void
    {
        $this->nameResolver->implementNames([
            'popcms:dbcolumn:orderby:users:post-count' => 'post_count',
        ]);
    }
}
