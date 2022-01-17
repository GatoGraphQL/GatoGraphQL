<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentsWP\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractResolutionSet;

class LooseContractResolutionSet extends AbstractLooseContractResolutionSet
{
    protected function resolveContracts(): void
    {
        $this->getNameResolver()->implementNames([
            'popcms:dbcolumn:orderby:comments:date' => 'comment_date_gmt',
            'popcms:dbcolumn:orderby:customposts:comment-count' => 'comment_count',
        ]);
    }
}
