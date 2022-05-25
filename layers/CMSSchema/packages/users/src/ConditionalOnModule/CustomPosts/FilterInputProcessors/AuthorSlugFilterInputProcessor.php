<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputProcessors;

use PoP\ComponentModel\FilterInputProcessors\AbstractValueToQueryFilterInputProcessor;

class AuthorSlugFilterInputProcessor extends AbstractValueToQueryFilterInputProcessor
{
    protected function getQueryArgKey(array $filterInput): string
    {
        return 'author-slug';
    }
}
