<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractValueToQueryFilterInputProcessor;

class ExcludeAuthorIDsFilterInputProcessor extends AbstractValueToQueryFilterInputProcessor
{
    protected function getQueryArgKey(array $filterInput): string
    {
        return 'exclude-author-ids';
    }
}
