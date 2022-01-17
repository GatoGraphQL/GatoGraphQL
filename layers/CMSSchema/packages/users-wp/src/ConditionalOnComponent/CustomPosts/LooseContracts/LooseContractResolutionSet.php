<?php

declare(strict_types=1);

namespace PoPCMSSchema\UsersWP\ConditionalOnComponent\CustomPosts\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractResolutionSet;

class LooseContractResolutionSet extends AbstractLooseContractResolutionSet
{
    protected function resolveContracts(): void
    {
        $this->getNameResolver()->implementNames([
            'popcms:dbcolumn:orderby:users:post-count' => 'post_count',
        ]);
    }
}
