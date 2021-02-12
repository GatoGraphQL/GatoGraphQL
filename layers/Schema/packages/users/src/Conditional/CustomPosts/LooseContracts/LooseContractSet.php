<?php

declare(strict_types=1);

namespace PoPSchema\Users\Conditional\CustomPosts\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractSet;

class LooseContractSet extends AbstractLooseContractSet
{
    /**
     * @return string[]
     */
    public function getRequiredNames(): array
    {
        return [
            // DB Columns
            'popcms:dbcolumn:orderby:users:post-count',
        ];
    }
}
